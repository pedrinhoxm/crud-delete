<?php
require_once 'banco.php';

$usuario = null;
$mensagem = '';

if (isset($_GET['edit_id']) && is_numeric($_GET['edit_id'])) {
    $id = filter_input(INPUT_GET, 'edit_id', FILTER_SANITIZE_NUMBER_INT);
    
    $sql_select = "SELECT id, nome, email FROM usuarios WHERE id = ?";
    $stmt_select = $pdo->prepare($sql_select);
    $stmt_select->bindValue(1, $id, PDO::PARAM_INT);
    $stmt_select->execute();
    
    $usuario = $stmt_select->fetch(PDO::FETCH_ASSOC);
    
    if (!$usuario) {
        header('Location: index.php?msg=error_not_found');
        exit();
    }
    
} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);

    if (empty($nome) || empty($email)) {
        $mensagem = '<div class="alert alert-danger">Por favor, preencha todos os campos!</div>';
        $sql_select = "SELECT id, nome, email FROM usuarios WHERE id = ?";
        $stmt_select = $pdo->prepare($sql_select);
        $stmt_select->bindValue(1, $id, PDO::PARAM_INT);
        $stmt_select->execute();
        $usuario = $stmt_select->fetch(PDO::FETCH_ASSOC);
        
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensagem = '<div class="alert alert-danger">Por favor, insira um e-mail válido!</div>';
        $sql_select = "SELECT id, nome, email FROM usuarios WHERE id = ?";
        $stmt_select = $pdo->prepare($sql_select);
        $stmt_select->bindValue(1, $id, PDO::PARAM_INT);
        $stmt_select->execute();
        $usuario = $stmt_select->fetch(PDO::FETCH_ASSOC);
        
    } else {
        $sql_update = "UPDATE usuarios SET nome = ?, email = ? WHERE id = ?";
        
        try {
            $stmt_update = $pdo->prepare($sql_update);
            $stmt_update->bindValue(1, $nome);
            $stmt_update->bindValue(2, $email);
            $stmt_update->bindValue(3, $id, PDO::PARAM_INT);
            $stmt_update->execute();
            header('Location: index.php?msg=success_update');
            exit();
            
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $mensagem = '<div class="alert alert-danger">Este e-mail já está cadastrado para outro usuário!</div>';
            } else {
                $mensagem = '<div class="alert alert-danger">Erro ao atualizar: ' . $e->getMessage() . '</div>';
            }

            $sql_select = "SELECT id, nome, email FROM usuarios WHERE id = ?";
            $stmt_select = $pdo->prepare($sql_select);
            $stmt_select->bindValue(1, $id, PDO::PARAM_INT);
            $stmt_select->execute();
            $usuario = $stmt_select->fetch(PDO::FETCH_ASSOC);
        }
    }
} else {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin-top: 50px;
        }
        .card {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($usuario): ?>
        <div class="card">
            <div class="card-header bg-warning text-white">
                <h4 class="mb-0"><i class="bi bi-pencil-square"></i> Editar Usuário</h4>
            </div>
            <div class="card-body">
                <?php echo $mensagem; ?>
                
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> 
                    Editando: <strong><?= htmlspecialchars($usuario['nome']); ?></strong>
                </div>
                
                <form method="POST" action="editar.php">
                    <input type="hidden" name="id" value="<?= $usuario['id']; ?>">
                    
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome:</label>
                        <input type="text" 
                               class="form-control" 
                               id="nome" 
                               name="nome" 
                               value="<?= htmlspecialchars($usuario['nome']); ?>" 
                               placeholder="Digite o nome completo"
                               required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail:</label>
                        <input type="email" 
                               class="form-control" 
                               id="email" 
                               name="email" 
                               value="<?= htmlspecialchars($usuario['email']); ?>" 
                               placeholder="exemplo@email.com"
                               required>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-warning text-white">
                            <i class="bi bi-save"></i> Salvar Alterações
                        </button>
                        <a href="index.php" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
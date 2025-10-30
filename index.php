<?php
require_once 'banco.php';

$mensagem = '';
if (isset($_GET['msg'])) {
    switch ($_GET['msg']) {
        case 'success_update':
            $mensagem = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> Usuário atualizado com sucesso!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>';
            break;
        case 'error_not_found':
            $mensagem = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-x-circle"></i> Usuário não encontrado!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>';
            break;
        case 'error_update':
            $mensagem = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-x-circle"></i> Erro ao atualizar usuário!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>';
            break;
    }
}

try {
    $sql = "SELECT * FROM usuarios ORDER BY id DESC";
    $stmt = $pdo->query($sql);
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Erro ao buscar usuários: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema CRUD - PHP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        .header-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .table-container {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .btn-add {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px 30px;
            font-weight: 500;
        }
        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .action-buttons .btn {
            margin: 0 2px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-section text-center">
            <h1><i class="bi bi-database"></i> Sistema CRUD - PHP</h1>
            <p class="mb-0">Gerenciamento de Usuários</p>
        </div>

        <div class="table-container">
            <?php echo $mensagem; ?>
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0"><i class="bi bi-people"></i> Lista de Usuários</h4>
                <a href="add.php" class="btn btn-primary btn-add">
                    <i class="bi bi-plus-circle"></i> Novo Usuário
                </a>
            </div>

            <?php if (count($usuarios) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th width="10%">ID</th>
                                <th width="35%">Nome</th>
                                <th width="35%">E-mail</th>
                                <th width="20%" class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usuarios as $usuario): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($usuario['id']); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['nome']); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                                    <td class="text-center action-buttons">
                                        <a href="editar.php?edit_id=<?php echo $usuario['id']; ?>" 
                                           class="btn btn-sm btn-warning" 
                                           title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button class="btn btn-sm btn-danger" 
                                                title="Excluir"
                                                onclick="confirmarExclusao(<?php echo $usuario['id']; ?>)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="alert alert-info mt-3">
                    <i class="bi bi-info-circle"></i> 
                    Total de usuários cadastrados: <strong><?php echo count($usuarios); ?></strong>
                </div>
            <?php else: ?>
                <div class="alert alert-warning text-center">
                    <i class="bi bi-exclamation-triangle"></i>
                    <h5>Nenhum usuário cadastrado</h5>
                    <p>Clique no botão "Novo Usuário" para adicionar o primeiro usuário.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmarExclusao(id) {
            if (confirm('Tem certeza que deseja excluir este usuário?')) {
                alert('Função de exclusão será implementada em breve!');
            }
        }
    </script>
</body>
</html>
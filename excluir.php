<?php
require 'banco.php'; // Alterado de 'conexao.php' para 'banco.php'

// Verifica se o ID para exclusão foi recebido
if (isset($_GET['delete_id']) && is_numeric($_GET['delete_id'])) {
    $id_para_deletar = filter_input(INPUT_GET, 'delete_id', FILTER_SANITIZE_NUMBER_INT);

    $sql = "DELETE FROM usuarios WHERE id = ?";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(1, $id_para_deletar, PDO::PARAM_INT);
        $stmt->execute();

        // Redireciona de volta para a página principal
        header('Location: index.php?msg=success_del');
        exit();
    } catch (PDOException $e) {
        // Redireciona com uma mensagem de erro em caso de falha
        header('Location: index.php?msg=error_del&error=' . urlencode($e->getMessage()));
        exit();
    }
} else {
    // Se nenhum ID for fornecido, redireciona de volta
    header('Location: index.php?msg=error_no_id');
    exit();
}
?>

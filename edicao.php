<?php
function conectaBD() {
    $pdo = new PDO("mysql:host=143.106.241.3;dbname=cl201240;charset=utf8", "cl201240", "cl*06112005");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
}

function listarRoedores() {
    try {
        $pdo = conectaBD();

        $stmt = $pdo->query("SELECT id, nome FROM Roedores");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return false;
    }
}

function atualizarRoedor($id, $novoNome) {
    try {
        $pdo = conectaBD();

        $stmt = $pdo->prepare("UPDATE Roedores SET nome = :novoNome WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':novoNome', $novoNome);
        $stmt->execute();

        return true;
    } catch (PDOException $e) {
        return false;
    }
}

if ($_SERVER["REQUEST_METHOD"] === 'POST' && isset($_POST['editar'])) {
    $roedor_id = $_POST['editar'];
    $novoNome = $_POST['novoNome'];

    if (atualizarRoedor($roedor_id, $novoNome)) {
        $mensagem = "Nome do roedor atualizado com sucesso!";
    } else {
        $mensagem = "Falha ao atualizar o nome do roedor.";
    }
}

$roedores = listarRoedores();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edição de Roedores</title>
    <style>
    </style>
</head>
<body>
    <h1>Edição de Roedores</h1>
    <div class="container">
        <?php if ($roedores) : ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Editar</th>
                </tr>
                <?php foreach ($roedores as $roedor) : ?>
                    <tr>
                        <td><?php echo $roedor['id']; ?></td>
                        <td>
                            <?php if (isset($_POST['editar']) && $_POST['editar'] == $roedor['id']) : ?>
                                <form method="POST">
                                    <input type="text" name="novoNome" value="<?php echo $roedor['nome']; ?>">
                                    <input type="hidden" name="editar" value="<?php echo $roedor['id']; ?>">
                                    <button type="submit">Confirmar</button>
                                </form>
                            <?php else : ?>
                                <?php echo $roedor['nome']; ?>
                                <form method="POST">
                                    <input type="hidden" name="editar" value="<?php echo $roedor['id']; ?>">
                                    <button type="submit">Editar</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else : ?>
            <p>Nenhum roedor cadastrado.</p>
        <?php endif; ?>

        <?php if (isset($mensagem)) : ?>
            <p><?php echo $mensagem; ?></p>
        <?php endif; ?>

        <br>
        <a href="index.php">Voltar para a Página Inicial</a>
    </div>
</body>
</html>
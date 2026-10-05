<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Editar Veículo</title>
</head>

<body>
    <h1>Editar Veículo</h1>

    <form action="<?= base_url('veiculos/atualizar/' . $veiculo['VEI_ID']) ?>" method="POST">
        <label>Placa:</label><br>
        <input type="text" id="placa" name="placa" value="<?= $veiculo['VEI_PLACA'] ?>" required>
        <br><br>
        <label>Marca:</label><br>
        <input type="text" id="marca" name="marca" value="<?= $veiculo['VEI_MARCA'] ?>" required>
        <br><br>
        <label>Modelo:</label><br>
        <input type="text" id="modelo" name="modelo" value="<?= $veiculo['VEI_MODELO'] ?>" required>
        <br><br>
        <label>Ano:</label><br>
        <input type="text" id="ano" name="ano" value="<?= $veiculo['VEI_ANO'] ?>" required>
        <br><br>
        <label>Cliente:</label><br>
        <select id="cliente" name="cliente" required>
            <?php foreach ($clientes as $cliente): ?>
                <option value="<?= $cliente['CLI_ID'] ?>" <?= $cliente['CLI_ID'] == $veiculo['FK_CLI_ID'] ? 'selected' : '' ?>>
                    <?= $cliente['CLI_NOME'] ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br><br>
        <input type="submit" id="editar_veiculo" name="editar_veiculo" value="Salvar Alterações">
    </form>

    <br>
    <a href="<?= base_url('veiculos') ?>"><button>Voltar</button></a>
</body>

</html>
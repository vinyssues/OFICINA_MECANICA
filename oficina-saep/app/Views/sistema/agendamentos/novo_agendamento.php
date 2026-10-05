<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Novo Agendamento</title>
</head>

<body>
    <h1>Novo Agendamento</h1>
    <form action="<?= base_url('agendamentos/inserir') ?>" method="POST">
        <label>Date e Hora:</label><br>
        <input type="date" id="data" name="data" required>

        <br><br>

        <label>Serviço:</label><br>
        <input type="text" id="servico" name="servico" placeholder="Serviço..." required>

        <br><br>

        <label>Status:</label><br>
        <input type="text" id="status" name="status" placeholder="Status..." required>

        <br><br>

        <label>Cliente:</label><br>
        <select id="cliente" name="cliente" required>
            <option value="">Selecione um cliente</option>
            <?php foreach ($clientes as $cliente): ?>
                <option value="<?= $cliente['CLI_ID'] ?>">
                    <?= $cliente['CLI_NOME'] ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <label>Veículo:</label><br>
        <select id="veiculo" name="veiculo" required>
            <option value="">Selecione um veículo</option>
            <?php foreach ($veiculos as $veiculo): ?>
                <option value="<?= $veiculo['VEI_ID'] ?>">
                    <?= $veiculo['VEI_PLACA'] ?> - <?= $veiculo['VEI_MODELO'] ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <input type="submit" id="cadastrar_agendamento" name="cadastrar_agendamento" value="Cadastrar">
    </form>
    <br>
    <a href="<?= base_url('agendamentos') ?>"><button>Voltar</button></a>
</body>

</html>
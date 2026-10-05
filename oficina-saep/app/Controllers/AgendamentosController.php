<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\AgendamentosModel;
use App\Models\ClientesModel;
use App\Models\VeiculosModel;

// Vincula o Agendamento ao Cliente (FK_CLI_ID) e ao Veículo (FK_VEI_ID)
class AgendamentosController extends BaseController
{
    // Exibe a listagem de agendamentos
    public function index()
    {
        // Instancia o Model de agendamento
        $model = new AgendamentosModel();

        // Verifica se o formulário de pesquisa foi enviado
        if ($this->request->getPost('botao_pesquisar')) {

            // Recupera o termo digitado
            $pesquisar = $this->request->getPost('pesquisar');

            // Busca os agendamentos juntamente com
            // informações do cliente e do veículo
            $dados['agendamentos'] = $model
                ->select(
                    'AGENDAMENTOS.*,
                    CLIENTES.CLI_NOME,
                    VEICULOS.VEI_PLACA,
                    VEICULOS.VEI_MODELO'
                )

                // Relaciona o agendamento ao cliente
                ->join(
                    'CLIENTES',
                    'CLIENTES.CLI_ID = AGENDAMENTOS.FK_CLI_ID'
                )

                // Relaciona o agendamento ao veículo
                ->join(
                    'VEICULOS',
                    'VEICULOS.VEI_ID = AGENDAMENTOS.FK_VEI_ID'
                )

                // Agrupa as condições utilizadas na pesquisa
                ->groupStart()

                    // Pesquisa pelo nome do cliente
                    ->like('CLIENTES.CLI_NOME', $pesquisar)

                    // Pesquisa pela placa do veículo
                    ->orLike('VEICULOS.VEI_PLACA', $pesquisar)

                    // Pesquisa pelo modelo do veículo
                    ->orLike('VEICULOS.VEI_MODELO', $pesquisar)

                    // Pesquisa pelo serviço
                    ->orLike('AGE_SERVICO', $pesquisar)

                    // Pesquisa pelo status
                    ->orLike('AGE_STATUS', $pesquisar)

                ->groupEnd()

                // Ordena os agendamentos pela data e hora
                ->orderBy('AGE_DATA_HORA', 'ASC')

                // Executa a consulta
                ->findAll();
        }
        else {

            // Caso nenhuma pesquisa tenha sido realizada,
            // busca todos os agendamentos
            $dados['agendamentos'] = $model
                ->select(
                    'AGENDAMENTOS.*,
                    CLIENTES.CLI_NOME,
                    VEICULOS.VEI_PLACA,
                    VEICULOS.VEI_MODELO'
                )

                // Relaciona o cliente ao agendamento
                ->join(
                    'CLIENTES',
                    'CLIENTES.CLI_ID = AGENDAMENTOS.FK_CLI_ID'
                )

                // Relaciona o veículo ao agendamento
                ->join(
                    'VEICULOS',
                    'VEICULOS.VEI_ID = AGENDAMENTOS.FK_VEI_ID'
                )

                // Ordena pela data e hora do agendamento
                ->orderBy('AGE_DATA_HORA', 'ASC')

                // Executa a consulta
                ->findAll();
        }

        // Carrega a View com os agendamentos encontrados
        return view('sistema/agendamentos/index', $dados);
    }


    // Exibe o formulário para cadastrar um novo agendamento
    public function novo()
    {
        // Instancia o Model de cliente
        $clienteModel = new ClientesModel();

        // Instancia o Model de veículo
        $veiculoModel = new VeiculosModel();

        // Busca todos os clientes para preencher o SELECT
        $dados['clientes'] = $clienteModel->findAll();

        // Busca todos os veículos para preencher o SELECT
        $dados['veiculos'] = $veiculoModel->findAll();

        // Carrega o formulário
        return view(
            'sistema/agendamentos/novo_agendamento',
            $dados
        );
    }


    // Insere um novo agendamento
    public function inserir()
    {
        // Instancia o Model
        $model = new AgendamentosModel();

        // Recupera os dados enviados pelo formulário
        $dados = [
            'AGE_DATA_HORA' => $this->request->getPost('data'),
            'AGE_SERVICO'    => $this->request->getPost('servico'),
            'AGE_STATUS'    => $this->request->getPost('status'),

            // Cliente escolhido no formulário
            'FK_CLI_ID'     => $this->request->getPost('cliente'),

            // Veículo escolhido no formulário
            'FK_VEI_ID'     => $this->request->getPost('veiculo')
        ];

        // Insere o agendamento no banco
        $model->insert($dados);

        // Redireciona para a listagem
        return redirect()
            ->to(base_url('agendamentos'))
            ->with('success', 'Agendamento cadastrado com sucesso!');
    }


    // Exibe o formulário de edição
    public function editar($id)
    {
        // Instancia os três Models necessários
        $agendamentoModel = new AgendamentosModel();
        $clienteModel     = new ClientesModel();
        $veiculoModel     = new VeiculosModel();

        // Busca o agendamento pelo ID
        $dados['agendamento'] = $agendamentoModel->find($id);

        // Busca os clientes para preencher o SELECT
        $dados['clientes'] = $clienteModel->findAll();

        // Busca os veículos para preencher o SELECT
        $dados['veiculos'] = $veiculoModel->findAll();

        // Carrega a View de edição
        return view(
            'sistema/agendamentos/editar_agendamento',
            $dados
        );
    }


    // Atualiza um agendamento
    public function atualizar($id)
    {
        // Instancia o Model
        $model = new AgendamentosModel();

        // Recupera os novos valores enviados pelo formulário
        $dados = [
            'AGE_DATA_HORA' => $this->request->getPost('data'),
            'AGE_SERVICO'    => $this->request->getPost('servico'),
            'AGE_STATUS'    => $this->request->getPost('status'),
            'FK_CLI_ID'     => $this->request->getPost('cliente'),
            'FK_VEI_ID'     => $this->request->getPost('veiculo')
        ];

        // Atualiza o agendamento no banco
        $model->update($id, $dados);

        // Redireciona para a listagem
        return redirect()
            ->to(base_url('agendamentos'))
            ->with('success', 'Agendamento atualizado com sucesso!');
    }


    // Exclui um agendamento
    public function excluir($id)
    {
        // Instancia o Model
        $model = new AgendamentosModel();

        // Exclui o agendamento pelo ID
        $model->delete($id);

        // Redireciona para a listagem
        return redirect()
            ->to(base_url('agendamentos'))
            ->with('success', 'Agendamento excluído com sucesso!');
    }
}
<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\VeiculosModel;
use App\Models\ClientesModel;

// Vincula o Veículo ao seu respectivo Cliente (FK_CLI_ID)
class VeiculosController extends BaseController
{
    // Exibe a listagem de veículos
    public function index()
    {
        // Instancia o Model de veículo
        $model = new VeiculosModel();

        // Verifica se o formulário de pesquisa foi enviado
        if ($this->request->getPost('botao_pesquisar')) {

            // Recupera o texto digitado pelo usuário
            $pesquisar = $this->request->getPost('pesquisar');

            // Busca os veículos juntamente com o nome do cliente
            // responsável por cada veículo
            $dados['veiculos'] = $model
                ->select('VEICULOS.*, CLIENTES.CLI_NOME')

                // Relaciona VEICULO com CLIENTE pela chave estrangeira
                ->join(
                    'CLIENTES',
                    'CLIENTES.CLI_ID = VEICULOS.FK_CLI_ID'
                )

                // Agrupa as condições da pesquisa
                ->groupStart()

                    // Pesquisa pela placa
                    ->like('VEI_PLACA', $pesquisar)

                    // Pesquisa pelo modelo
                    ->orLike('VEI_MODELO', $pesquisar)

                    // Pesquisa pela marca
                    ->orLike('VEI_MARCA', $pesquisar)

                    // Também permite pesquisar pelo nome do cliente
                    ->orLike('CLIENTES.CLI_NOME', $pesquisar)

                ->groupEnd()

                // Executa a consulta
                ->findAll();
        }
        else {

            // Caso não exista pesquisa, busca todos os veículos
            // juntamente com o nome dos respectivos clientes
            $dados['veiculos'] = $model
                ->select('VEICULOS.*, CLIENTES.CLI_NOME')
                ->join(
                    'CLIENTES',
                    'CLIENTES.CLI_ID = VEICULOS.FK_CLI_ID'
                )
                ->findAll();
        }

        // Carrega a View de veículos
        return view('sistema/veiculos/index', $dados);
    }


    // Exibe o formulário para cadastrar um novo veículo
    public function novo()
    {
        // Instancia o Model de cliente
        $clienteModel = new ClientesModel();

        // Busca todos os clientes cadastrados
        // Esses dados serão utilizados em um campo SELECT
        $dados['clientes'] = $clienteModel->findAll();

        // Carrega o formulário de cadastro do veículo
        return view('sistema/veiculos/novo_veiculo', $dados);
    }


    // Insere um novo veículo
    public function inserir()
    {
        // Instancia o Model de veículo
        $model = new VeiculosModel();

        // Recupera os dados enviados pelo formulário
        $dados = [
            'VEI_PLACA'  => $this->request->getPost('placa'),
            'VEI_MODELO' => $this->request->getPost('modelo'),
            'VEI_MARCA'  => $this->request->getPost('marca'),
            'VEI_ANO'    => $this->request->getPost('ano'),

            // Guarda o ID do cliente escolhido no formulário
            // como chave estrangeira do veículo
            'FK_CLI_ID'  => $this->request->getPost('cliente')
        ];

        // Insere o veículo no banco
        $model->insert($dados);

        // Redireciona para a listagem
        return redirect()
            ->to(base_url('veiculos'))
            ->with('success', 'Veículo cadastrado com sucesso!');
    }


    // Exibe o formulário de edição
    public function editar($id)
    {
        // Instancia o Model de veículos
        $veiculoModel = new VeiculosModel();

        // Instancia o Model de clientes
        $clienteModel = new ClientesModel();

        // Busca o veículo que será editado
        $dados['veiculo'] = $veiculoModel->find($id);

        // Busca todos os clientes para preencher o SELECT
        $dados['clientes'] = $clienteModel->findAll();

        // Carrega a View de edição
        return view('sistema/veiculos/editar_veiculo', $dados);
    }


    // Atualiza um veículo
    public function atualizar($id)
    {
        // Instancia o Model
        $model = new VeiculosModel();

        // Recupera os novos dados do formulário
        $dados = [
            'VEI_PLACA'  => $this->request->getPost('placa'),
            'VEI_MODELO' => $this->request->getPost('modelo'),
            'VEI_MARCA'  => $this->request->getPost('marca'),
            'VEI_ANO'    => $this->request->getPost('ano'),
            'FK_CLI_ID'  => $this->request->getPost('cliente')
        ];

        // Atualiza o veículo pelo ID
        $model->update($id, $dados);

        // Redireciona para a listagem
        return redirect()
            ->to(base_url('veiculos'))
            ->with('success', 'Veículo atualizado com sucesso!');
    }


    // Exclui um veículo
    public function excluir($id)
    {
        // Instancia o Model
        $model = new VeiculosModel();

        // Exclui o veículo pelo ID
        $model->delete($id);

        // Redireciona para a listagem
        return redirect()
            ->to(base_url('veiculos'))
            ->with('success', 'Veículo excluído com sucesso!');
    }
}
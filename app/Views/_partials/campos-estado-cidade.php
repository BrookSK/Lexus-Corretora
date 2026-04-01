<?php
/**
 * Partial: Dropdowns de Estado e Cidade
 * Variáveis esperadas:
 *   $estadoSelecionado (string|null) — UF selecionada
 *   $cidadeSelecionada (string|null) — cidade selecionada
 *   $campoEstado (string) — name do campo estado (default: 'state')
 *   $campoCidade (string) — name do campo cidade (default: 'city')
 *   $obrigatorio (bool) — se os campos são required (default: false)
 */
use LEX\Core\View;

$estadoSelecionado = $estadoSelecionado ?? '';
$cidadeSelecionada = $cidadeSelecionada ?? '';
$campoEstado = $campoEstado ?? 'state';
$campoCidade = $campoCidade ?? 'city';
$obrigatorio = $obrigatorio ?? false;
$req = $obrigatorio ? 'required' : '';

$_estados = [
    'AC' => 'Acre', 'AL' => 'Alagoas', 'AP' => 'Amapá', 'AM' => 'Amazonas',
    'BA' => 'Bahia', 'CE' => 'Ceará', 'DF' => 'Distrito Federal', 'ES' => 'Espírito Santo',
    'GO' => 'Goiás', 'MA' => 'Maranhão', 'MT' => 'Mato Grosso', 'MS' => 'Mato Grosso do Sul',
    'MG' => 'Minas Gerais', 'PA' => 'Pará', 'PB' => 'Paraíba', 'PR' => 'Paraná',
    'PE' => 'Pernambuco', 'PI' => 'Piauí', 'RJ' => 'Rio de Janeiro', 'RN' => 'Rio Grande do Norte',
    'RS' => 'Rio Grande do Sul', 'RO' => 'Rondônia', 'RR' => 'Roraima', 'SC' => 'Santa Catarina',
    'SP' => 'São Paulo', 'SE' => 'Sergipe', 'TO' => 'Tocantins',
];

$_cidadesPorEstado = [
    'AC' => ['Rio Branco','Cruzeiro do Sul','Sena Madureira','Tarauacá','Feijó'],
    'AL' => ['Maceió','Arapiraca','Rio Largo','Palmeira dos Índios','Penedo'],
    'AP' => ['Macapá','Santana','Laranjal do Jari','Oiapoque','Mazagão'],
    'AM' => ['Manaus','Parintins','Itacoatiara','Manacapuru','Coari'],
    'BA' => ['Salvador','Feira de Santana','Vitória da Conquista','Camaçari','Itabuna','Juazeiro','Lauro de Freitas','Ilhéus','Jequié','Teixeira de Freitas','Barreiras','Porto Seguro'],
    'CE' => ['Fortaleza','Caucaia','Juazeiro do Norte','Maracanaú','Sobral','Crato','Itapipoca','Maranguape','Iguatu'],
    'DF' => ['Brasília','Taguatinga','Ceilândia','Samambaia','Plano Piloto'],
    'ES' => ['Vitória','Vila Velha','Serra','Cariacica','Cachoeiro de Itapemirim','Linhares','São Mateus','Colatina','Guarapari'],
    'GO' => ['Goiânia','Aparecida de Goiânia','Anápolis','Rio Verde','Luziânia','Águas Lindas de Goiás','Valparaíso de Goiás','Trindade','Catalão'],
    'MA' => ['São Luís','Imperatriz','São José de Ribamar','Timon','Caxias','Codó','Paço do Lumiar','Açailândia'],
    'MT' => ['Cuiabá','Várzea Grande','Rondonópolis','Sinop','Tangará da Serra','Cáceres','Sorriso','Lucas do Rio Verde'],
    'MS' => ['Campo Grande','Dourados','Três Lagoas','Corumbá','Ponta Porã','Naviraí','Nova Andradina','Aquidauana'],
    'MG' => ['Belo Horizonte','Uberlândia','Contagem','Juiz de Fora','Betim','Montes Claros','Ribeirão das Neves','Uberaba','Governador Valadares','Ipatinga','Sete Lagoas','Divinópolis','Santa Luzia','Poços de Caldas','Patos de Minas','Pouso Alegre','Teófilo Otoni','Barbacena','Varginha'],
    'PA' => ['Belém','Ananindeua','Santarém','Marabá','Parauapebas','Castanhal','Abaetetuba','Cametá','Marituba'],
    'PB' => ['João Pessoa','Campina Grande','Santa Rita','Patos','Bayeux','Sousa','Cabedelo','Cajazeiras'],
    'PR' => ['Curitiba','Londrina','Maringá','Ponta Grossa','Cascavel','São José dos Pinhais','Foz do Iguaçu','Colombo','Guarapuava','Paranaguá','Araucária','Toledo','Apucarana','Campo Largo'],
    'PE' => ['Recife','Jaboatão dos Guararapes','Olinda','Caruaru','Petrolina','Paulista','Cabo de Santo Agostinho','Camaragibe','Garanhuns','Vitória de Santo Antão'],
    'PI' => ['Teresina','Parnaíba','Picos','Piripiri','Floriano','Campo Maior'],
    'RJ' => ['Rio de Janeiro','São Gonçalo','Duque de Caxias','Nova Iguaçu','Niterói','Belford Roxo','São João de Meriti','Campos dos Goytacazes','Petrópolis','Volta Redonda','Magé','Itaboraí','Macaé','Mesquita','Nilópolis','Cabo Frio','Angra dos Reis','Resende','Teresópolis','Maricá'],
    'RN' => ['Natal','Mossoró','Parnamirim','São Gonçalo do Amarante','Macaíba','Ceará-Mirim','Caicó','Açu'],
    'RS' => ['Porto Alegre','Caxias do Sul','Pelotas','Canoas','Santa Maria','Gravataí','Viamão','Novo Hamburgo','São Leopoldo','Rio Grande','Alvorada','Passo Fundo','Sapucaia do Sul','Cachoeirinha','Santa Cruz do Sul','Uruguaiana','Bento Gonçalves'],
    'RO' => ['Porto Velho','Ji-Paraná','Ariquemes','Vilhena','Cacoal','Rolim de Moura'],
    'RR' => ['Boa Vista','Rorainópolis','Caracaraí','Alto Alegre','Pacaraima'],
    'SC' => ['Florianópolis','Joinville','Blumenau','São José','Chapecó','Criciúma','Itajaí','Jaraguá do Sul','Lages','Palhoça','Balneário Camboriú','Brusque','Tubarão','São Bento do Sul','Caçador','Concórdia'],
    'SP' => ['São Paulo','Guarulhos','Campinas','São Bernardo do Campo','Santo André','São José dos Campos','Osasco','Ribeirão Preto','Sorocaba','Mauá','São José do Rio Preto','Santos','Mogi das Cruzes','Diadema','Jundiaí','Piracicaba','Carapicuíba','Bauru','Itaquaquecetuba','São Vicente','Franca','Praia Grande','Guarujá','Taubaté','Limeira','Suzano','Taboão da Serra','Sumaré','Barueri','Embu das Artes','Indaiatuba','Cotia','Marília','Americana','Araraquara','Jacareí','Hortolândia','Presidente Prudente','Rio Claro','Araçatuba','Ferraz de Vasconcelos','Santa Bárbara d\'Oeste','Itapevi','Valinhos','São Caetano do Sul','Botucatu','Atibaia','Bragança Paulista'],
    'SE' => ['Aracaju','Nossa Senhora do Socorro','Lagarto','Itabaiana','São Cristóvão','Estância'],
    'TO' => ['Palmas','Araguaína','Gurupi','Porto Nacional','Paraíso do Tocantins'],
];
?>
<div class="form-group">
  <label><?php echo View::e($labelEstado ?? 'Estado'); ?> <?php echo $obrigatorio ? '*' : ''; ?></label>
  <select name="<?php echo View::e($campoEstado); ?>" id="selectEstado_<?php echo View::e($campoEstado); ?>" <?php echo $req; ?> onchange="carregarCidades_<?php echo View::e($campoEstado); ?>(this.value)">
    <option value="">Selecione o estado...</option>
    <?php foreach ($_estados as $uf => $nome): ?>
    <option value="<?php echo View::e($uf); ?>" <?php echo $estadoSelecionado === $uf ? 'selected' : ''; ?>><?php echo View::e($uf . ' — ' . $nome); ?></option>
    <?php endforeach; ?>
  </select>
</div>
<div class="form-group">
  <label><?php echo View::e($labelCidade ?? 'Cidade'); ?> <?php echo $obrigatorio ? '*' : ''; ?></label>
  <select name="<?php echo View::e($campoCidade); ?>" id="selectCidade_<?php echo View::e($campoEstado); ?>" <?php echo $req; ?>>
    <option value="">Selecione o estado primeiro...</option>
  </select>
</div>
<script>
(function(){
  var cidadesPorEstado = <?php echo json_encode($_cidadesPorEstado, JSON_UNESCAPED_UNICODE); ?>;
  var estadoAtual = <?php echo json_encode($estadoSelecionado, JSON_UNESCAPED_UNICODE); ?>;
  var cidadeAtual = <?php echo json_encode($cidadeSelecionada, JSON_UNESCAPED_UNICODE); ?>;

  window['carregarCidades_<?php echo View::e($campoEstado); ?>'] = function(uf) {
    var sel = document.getElementById('selectCidade_<?php echo View::e($campoEstado); ?>');
    sel.innerHTML = '<option value="">Selecione a cidade...</option>';
    if (uf && cidadesPorEstado[uf]) {
      cidadesPorEstado[uf].forEach(function(c) {
        var o = document.createElement('option');
        o.value = c; o.textContent = c;
        if (c === cidadeAtual) o.selected = true;
        sel.appendChild(o);
      });
      // Opção "Outra" para cidades não listadas
      var outra = document.createElement('option');
      outra.value = '__outra__'; outra.textContent = '— Outra cidade —';
      sel.appendChild(outra);
    }
    sel.onchange = function() {
      if (this.value === '__outra__') {
        var nome = prompt('Digite o nome da cidade:');
        if (nome && nome.trim()) {
          var o = document.createElement('option');
          o.value = nome.trim(); o.textContent = nome.trim(); o.selected = true;
          sel.insertBefore(o, sel.lastChild);
        } else {
          this.value = '';
        }
      }
    };
  };

  if (estadoAtual) {
    window['carregarCidades_<?php echo View::e($campoEstado); ?>'](estadoAtual);
  }
})();
</script>

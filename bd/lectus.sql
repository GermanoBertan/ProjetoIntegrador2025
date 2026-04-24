-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 24/04/2026 às 16:49
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `lectus`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `autor`
--

CREATE TABLE `autor` (
  `codAutor` int(11) NOT NULL,
  `autor` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `autor`
--

INSERT INTO `autor` (`codAutor`, `autor`) VALUES
(1, 'Machado de Assis'),
(2, 'Clarice Lispector'),
(3, 'J.K. Rowling'),
(4, 'Jorge Amado'),
(5, 'Carlos Drummond de Andrade'),
(6, 'Graciliano Ramos'),
(7, 'Mário de Andrade'),
(8, 'João Guimarães Rosa'),
(9, 'José de Alencar'),
(10, 'Castro Alves'),
(11, 'Aluísio Azevedo'),
(12, 'Gonçalves Dias'),
(13, 'Cecília Meireles'),
(14, 'Monteiro Lobato'),
(15, 'Paulo Coelho'),
(16, 'Lima Barreto'),
(17, 'Rubem Fonseca'),
(18, 'Manoel de Barros'),
(19, 'Ariano Suassuna'),
(20, 'Conceição Evaristo'),
(21, 'Carlos Heitor Cony'),
(22, 'William Shakespeare'),
(23, 'Jane Austen'),
(24, 'Charles Dickens'),
(25, 'Fyodor Dostoyevsky'),
(26, 'Leo Tolstoy'),
(27, 'Gabriel García Márquez'),
(28, 'George Orwell'),
(29, 'Ernest Hemingway'),
(36, 'Virginia Woolf'),
(37, 'J. R. R. Tolkien'),
(38, 'Dante Alighieri'),
(39, 'Luís de Camões'),
(40, 'Franz Kafka'),
(42, 'Matt Haig'),
(43, 'Érico Veríssimo');

-- --------------------------------------------------------

--
-- Estrutura para tabela `categoria`
--

CREATE TABLE `categoria` (
  `codCategoria` int(11) NOT NULL,
  `categoria` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `categoria`
--

INSERT INTO `categoria` (`codCategoria`, `categoria`) VALUES
(1, 'Antologia de crônicas'),
(2, 'Romance'),
(3, 'Romance'),
(4, 'Conto'),
(5, 'Literatura Brasileira'),
(6, 'Poesia'),
(7, 'Teatro'),
(8, 'Ficção Científica'),
(9, 'Fantasia'),
(10, 'Fantasia Épica'),
(11, 'Ficção Histórica'),
(12, 'Suspense'),
(13, 'Mistério'),
(14, 'Terror'),
(15, 'Horror'),
(16, 'Aventura'),
(17, 'Distopia'),
(18, 'Biografia'),
(19, 'Autobiografia'),
(20, 'Memórias'),
(21, 'Ensaio'),
(22, 'Crônica'),
(23, 'Literatura Juvenil'),
(24, 'Infantojuvenil'),
(25, 'Realismo'),
(26, 'Naturalismo'),
(27, 'Ficção Feminista'),
(28, 'Ficção LGBTQIA+'),
(29, 'Ficção Filosófica'),
(30, 'Ficção Satírica'),
(31, 'Literatura de Cordel'),
(32, 'Literatura Clássica'),
(33, 'Drama'),
(34, 'Tragédia'),
(35, 'Ficção Literária ');

-- --------------------------------------------------------

--
-- Estrutura para tabela `comentario`
--

CREATE TABLE `comentario` (
  `codComentario` int(11) NOT NULL,
  `comentario` longtext NOT NULL,
  `data` datetime DEFAULT NULL,
  `fk_livro` int(11) DEFAULT NULL,
  `fk_usuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `comentario`
--

INSERT INTO `comentario` (`codComentario`, `comentario`, `data`, `fk_livro`, `fk_usuario`) VALUES
(7, 'Acho super diferente a crítica que foi feita na obra modernista, eu li por vontade própria, mas pra quem estuda é super legal citar em redações. Mas, é uma obra necessária', '2025-11-24 10:54:31', 21, 1),
(9, 'Você vai se deparar com vários trechos de monólogos e planos onde você percebe como o conhecimento dessa traição afeta a sanidade do príncipe, que muda de comportamento e de atitudes em função do seu plano de vingança. Até de sua amada Ofélia e de seus amigos, ele se afasta.', '2025-11-27 08:53:50', 18, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `edicao`
--

CREATE TABLE `edicao` (
  `codEdicao` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `autorImagem` varchar(100) NOT NULL,
  `URL` longtext NOT NULL,
  `fk_livro` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `edicao`
--

INSERT INTO `edicao` (`codEdicao`, `nome`, `autorImagem`, `URL`, `fk_livro`) VALUES
(6, 'Capa Dura', 'Olly Moss', 'https://m.media-amazon.com/images/I/81ibfYk4qmL._SY425_.jpg', 6),
(7, 'Capa Dura', 'Olly Moss', 'https://m.media-amazon.com/images/I/81jbivNEVML._SY425_.jpg', 7),
(8, 'Capa Dura', 'Olly Moss', 'https://m.media-amazon.com/images/I/81u+ljPVifL._SY425_.jpg', 8),
(9, 'Capa Dura', 'Olly Moss', 'https://m.media-amazon.com/images/I/81nTLN-kz7L._SY425_.jpg', 9),
(10, 'Capa Dura', 'Olly Moss', 'https://m.media-amazon.com/images/I/81d6ESyPZwL._SY425_.jpg', 10),
(11, 'Capa Dura', 'Olly Moss', 'https://m.media-amazon.com/images/I/81yFIh1yoZL._SY425_.jpg', 11),
(12, 'Capa Dura', 'Olly Moss', 'https://m.media-amazon.com/images/I/81rvO7xcJOL._SY425_.jpg', 12),
(13, 'Amazon', '', 'https://m.media-amazon.com/images/I/61j6f5DOEkL._SY466_.jpg', 13),
(14, 'Amazon', '', 'https://m.media-amazon.com/images/I/61pMh14GJ1L._SY466_.jpg', 14),
(15, 'Amazon', '', 'https://m.media-amazon.com/images/I/91ECsm6QRvL._SY425_.jpg', 15),
(16, 'Amazon', '', 'https://m.media-amazon.com/images/I/81X2-rTPT+L._SY466_.jpg', 16),
(17, 'Amazon', '', 'https://m.media-amazon.com/images/I/916WkSH4cGL._SY425_.jpg', 17),
(18, 'Amazon', '', 'https://m.media-amazon.com/images/I/81D314P1V9L._SY466_.jpg', 18),
(19, 'Amazon', '', 'https://m.media-amazon.com/images/I/71QLwli7GUL._SY466_.jpg', 19),
(20, 'Amazon', '', 'https://m.media-amazon.com/images/I/81tpHtxgSML._SY425_.jpg', 20),
(21, 'Grupo Opni', 'Grupo Opni', 'https://m.media-amazon.com/images/I/71U-SBuim+L._SY425_.jpg', 21),
(23, 'Capa Dura', '', 'img/6924984138aef_A biblioteca da meia-noite.jpg', 23),
(24, 'Capa Comun', 'Paulo Von Poser', 'img/6928429e0fd5e_olhais os lírios do campo.jpg', 24);

-- --------------------------------------------------------

--
-- Estrutura para tabela `editora`
--

CREATE TABLE `editora` (
  `codEditora` int(11) NOT NULL,
  `editora` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `editora`
--

INSERT INTO `editora` (`codEditora`, `editora`) VALUES
(1, 'Editora Saraiva'),
(2, 'Rocco'),
(3, 'Penguin-Companhia das Letras'),
(4, 'Grupo Editorial Record'),
(5, 'Globo Livros'),
(6, 'Intrínseca'),
(7, 'Zahar'),
(8, 'Leya Brasil'),
(9, 'Martins Fontes'),
(10, 'Moderna'),
(11, 'FTD Educação'),
(12, 'Objetiva'),
(13, 'Editora 34'),
(14, 'Cia. Melhoramentos'),
(15, 'Guanabara Koogan'),
(16, 'Via Leitura'),
(17, 'Nova Fronteira'),
(18, 'Panda Books'),
(19, 'Bertand Brasil');

-- --------------------------------------------------------

--
-- Estrutura para tabela `faixaetaria`
--

CREATE TABLE `faixaetaria` (
  `codFaixaEtaria` int(11) NOT NULL,
  `faixaEtaria` varchar(100) NOT NULL,
  `abreviacao` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `faixaetaria`
--

INSERT INTO `faixaetaria` (`codFaixaEtaria`, `faixaEtaria`, `abreviacao`) VALUES
(1, 'Livre', 'L'),
(2, '10 anos', '+10'),
(3, '15 anos', '+15'),
(4, '12 anos', '12'),
(5, '14 anos', '14'),
(6, '16 anos', '16'),
(7, '18 anos', '18');

-- --------------------------------------------------------

--
-- Estrutura para tabela `leitura`
--

CREATE TABLE `leitura` (
  `codLeitura` int(11) NOT NULL,
  `situacao` int(11) NOT NULL,
  `dataInicioLei` date DEFAULT NULL,
  `dataModificacaoLei` date DEFAULT NULL,
  `numPagina` int(11) DEFAULT NULL,
  `paginaAtual` int(11) DEFAULT NULL,
  `nota` int(11) DEFAULT NULL,
  `fk_livro` int(11) DEFAULT NULL,
  `fk_resenha` int(11) DEFAULT NULL,
  `fk_usuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `leitura`
--

INSERT INTO `leitura` (`codLeitura`, `situacao`, `dataInicioLei`, `dataModificacaoLei`, `numPagina`, `paginaAtual`, `nota`, `fk_livro`, `fk_resenha`, `fk_usuario`) VALUES
(15, 0, '2025-11-23', '2025-11-23', 208, 208, 5, 6, 10, 2),
(18, 2, NULL, NULL, NULL, 1, NULL, 9, 13, 2),
(19, 2, NULL, NULL, NULL, 1, NULL, 16, 14, 2),
(21, 0, '2025-11-24', '2025-11-24', 192, 192, 5, 14, 16, 2),
(23, 2, NULL, NULL, NULL, 1, NULL, 10, 18, 2);

-- --------------------------------------------------------

--
-- Estrutura para tabela `livro`
--

CREATE TABLE `livro` (
  `codLivro` int(11) NOT NULL,
  `titulo` varchar(250) NOT NULL,
  `descricao` longtext NOT NULL,
  `ISBN` varchar(11) NOT NULL,
  `nPagina` int(11) NOT NULL,
  `anoPublicacao` int(11) NOT NULL,
  `fk_faixaEtaria` int(11) DEFAULT NULL,
  `fk_editora` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `livro`
--

INSERT INTO `livro` (`codLivro`, `titulo`, `descricao`, `ISBN`, `nPagina`, `anoPublicacao`, `fk_faixaEtaria`, `fk_editora`) VALUES
(6, 'Harry Potter e a Pedra Filosofal', 'Harry Potter é um garoto cujos pais, feiticeiros, foram assassinados por um poderosíssimo bruxo quando ele ainda era um bebê. Ele foi levado, então, para a casa dos tios que nada tinham a ver com o sobrenatural. Pelo contrário. Até os 10 anos, Harry foi uma espécie de gata borralheira: maltratado pelos tios, herdava roupas velhas do primo gorducho, tinha óculos remendados e era tratado como um estorvo. No dia de seu aniversário de 11 anos, entretanto, ele parece deslizar por um buraco sem fundo, como o de Alice no país das maravilhas, que o conduz a um mundo mágico. Descobre sua verdadeira história e seu destino: ser um aprendiz de feiticeiro até o dia em que terá que enfrentar a pior força do mal, o homem que assassinou seus pais. O menino de olhos verde, magricela e desengonçado, tão habituado à rejeição, descobre, também, que é um herói no universo dos magos. Potter fica sabendo que é a única pessoa a ter sobrevivido a um ataque do tal bruxo do mal e essa é a causa da marca em forma de raio que ele carrega na testa. Ele não é um garoto qualquer, ele sequer é um feiticeiro qualquer ele é Harry Potter, símbolo de poder, resistência e um líder natural entre os sobrenaturais. A fábula, recheada de fantasmas, paredes que falam, caldeirões, sapos, unicórnios, dragões e gigantes, não é, entretanto, apenas um passatempo.', '8532530788', 208, 1997, 4, 2),
(7, 'Harry Potter e a Câmara Secreta', 'Depois de férias aborrecidas na casa dos tios trouxas, está na hora de Harry Potter voltar a estudar. Coisas acontecem, no entanto, para dificultar o regresso de Harry. Persistente e astuto, o herói não se deixa intimidar pelos obstáculos e, com a ajuda dos fiéis amigos Weasley, começa o ano letivo na Escola de Magia e Bruxaria de Hogwarts. As novidades não são poucas. Novos colegas, novos professores, muitas e boas descobertas e um grande e perigosos desafio. Alguém ou alguma coisa ameaça a segurança e a tranquilidade dos membros de Hogwarts.', '8532530796', 224, 1998, 4, 2),
(8, 'Harry Potter e o Prisioneiro de Azkaban', 'As aulas estão de volta à Hogwarts e Harry Potter não vê a hora de embarcar no expresso a vapor que o levará de volta à escola de bruxaria. Mais uma vez suas férias na rua dos Alfeneiros foi triste e solitária. Com muita ação, humor e magia, \'Harry Potter e o prisioneiro de Azkaban\' traz de volta o gigante atrapalhado Rúbeo Hagrid, o sábio diretor Alvo Dumbledore, a exigente professora de transformação Minerva MacGonagall e o novo mestre Lupin, que guarda grandes surpresas para Harry.', '853253080', 288, 1999, 4, 2),
(9, 'Harry Potter e o Cálice de Fogo', 'Nesta aventura, o feiticeiro cresceu e está com 14 anos. O início do ano letivo de Harry Potter reserva muitas emoções, mágicas, e acontecimentos inesperados, além de um novo torneio em que os alunos de Hogwarts terão de demonstrar todas as habilidade mágicas e nãomágicas que vêm adquirindo ao longo de suas vidas. Harry é escolhido pelo Cálice de Fogo para competir como um dos campeões de Hogwarts, tendo ao lado seus fiéis amigos. Muitos desafios, feitiços, poções e confusões estão reservados para Harry. Além disso, ele terá que lidar ainda com os problemas comuns da adolescência amor, amizade, aceitação e rejeição.', '8532530818', 480, 2000, 4, 2),
(10, 'Harry Potter e a Ordem da Fênix', 'Harry não é mais um garoto. Aos 15 anos, continua sofrendo a rejeição dos Dursdley, sua estranha família no mundo dos \'trouxas\'. Também continua contando com Rony Weasley e Hermione Granger, seus melhores amigos em Hogwarts, para levar adiante suas investigações e aventuras. Mas o bruxinho começa a sentir e descobrir coisas novas, como o primeiro amor e a sexualidade. Nos volumes anteriores, J. K. Rowling mostrou como Harry foi transformado em celebridade no mundo da magia por ter derrotado, ainda bebê, Voldemort, o todopoderoso bruxo das trevas que assassinou seus pais. Neste quinto livro da saga, o protagonista, numa crise típica da adolescência, tem ataques de mau humor com a perseguição da imprensa, que o segue por todos os lugares e chega a inventar declarações que nunca deu. Harry vai enfrentar as investidas de Voldemort sem a proteção de Dumbledore, já que o diretor de Hogwarts é afastado da escola. E vai ser sem a ajuda de seu protetor que o jovem herói enfrentará descobertas sobre a personalidade controversa de seu pai, Tiago Potter, e a morte de alguém muito próximo.', '8532530826', 640, 2001, 4, 2),
(11, 'Harry Potter e o Enigma do Príncipe', 'Harry Potter e o enigma do príncipe\' dá continuidade à saga do jovem bruxo Harry Potter a partir do ponto em que o livro anterior parou o momento em que fica provado que o poder de Voldemort e dos Comensais da Morte, seus seguidores, cresce mais a cada dia, em meio à batalha entre o bem e o mal. A onda de terror provocada pelo Lorde das Trevas estaria afetando, até mesmo, o mundo dos trouxas (nãobruxos), e sendo agravada pela ação dos dementadores, criaturas mágicas aterrorizantes que \'sugam\' a esperança e a felicidade das pessoas. Harry, que acabou de completar 16 anos, parte rumo ao sexto ano na Escola de Magia e Bruxaria de Hogwarts, animado e ao mesmo tempo apreensivo com a perspectiva de ter aulas particulares com o professor Dumbledore, o diretor da escola e o bruxo mais respeitado em toda comunidade mágica. Harry, longe de ser aquele menino magricela que vivia no quarto debaixo da escada na casa dos tios trouxas, é um dos principais nomes entre aqueles que lutam contra Voldemort, e se vê cada vez mais isolado à medida que os rumores de que ele é O Eleito o único capaz de derrotar o Lorde das Trevas, se espalham pelo mundo dos bruxos. Dois atentados contra a vida de estudantes, a certeza de Harry quanto ao envolvimento de Draco Malfoy com os Comensais da Morte e o comportamento de Snape, suspeito como sempre, adicionam ainda mais tensão ao já inquietante período. Apesar de tudo isso, Harry e os amigos são adolescentes típicos dividem tarefas escolares e dormitórios bagunçados, correm das aulas para os treinos de quadribol, e namoram.', '8532530834', 432, 2002, 4, 2),
(12, 'Harry Potter e as Relíquias da Morte', 'Harry Potter e as relíquias da morte\', de J.K. Rowling, é o sétimo e último livro da série. Voldemorte está cada vez mais forte e Harry Potter precisa encontrar e aniquilar as Horcruxes para enfraquecer o lorde e poder enfrentálo. Nessa busca desenfreada, contando apenas com os amigos Rony e Hermione, Harry descobre as Relíquias da Morte, que serão úteis na batalha do bem contra o mal.', '8532530842', 512, 2003, 4, 2),
(13, 'Dom Casmurro', 'Amizade, ciúme, adultério, paixão, rancor, amargura e ironia… Bentinho e Capitu, adolescentes apaixonados, fazem um juramento antes de ele partir para o seminário.\r\nEle consegue escapar da batina, casa-se com Capitu e segue sua vida feliz… até a morte do seu melhor amigo… Uma incrível história de amor na qual dúvidas e mistérios acompanham Dom Casmurro até a sua solitária velhice.\r\nNesta obra do Realismo, nota-se uma fixação pelo olhar dúbio de Capitu, que era extremamente objetiva e tinha força de caráter; a jovem conduz toda a ação, apesar de a trama romanesca predominar.\r\nCom certa influência teológica, referências a alguns santos, à igreja, pois Bentinho, o narrador, estuda por certo tempo no seminário, e sua mãe é extremamente beata, a história trata das intempéries da vida dele contadas por ele mesmo.', '8567097096', 192, 1899, 2, 16),
(14, 'Memórias Póstumas de Brás Cubas', 'A autobiografia de Brás Cubas começa quando morre o homem e nasce um autor.\r\nO filho de uma família da alta sociedade é um narrador-personagem que constantemente comenta a própria obra e provoca o leitor ao se dirigir diretamente a ele, relatando uma vida sem realizações e fadada ao vazio existencial.\r\nBrás Cubas pinta o pessimismo, a ironia e a indiferença da sociedade burguesa carioca de então.\r\nSeria a busca por esse retrato social que, a partir daí, guiaria os autores do fim do Segundo Império e influenciaria toda a literatura nacional.', '8567097185', 192, 1881, 5, 16),
(15, 'A Divina Comédia', 'Texto fundador da língua italiana, súmula da cosmovisão de toda uma época, monumento poético de rigor e beleza, obra magna da literatura universal. É fato que a \"Comédia\" merece esses e muitos outros adjetivos de louvor, incluindo o \"divina\" que Boccaccio lhe deu já no século XIV. Mas também é certo que, como bom clássico, este livro reserva a cada novo leitor a prazerosa surpresa de renascer revigorado, como vem fazendo de geração em geração há quase setecentos anos. A longa jornada dantesca através do Inferno, Purgatório e Paraíso é aqui oferecida na íntegra - com seus mais de 14 mil decassílabos divididos em cem cantos e três partes - na rigorosa tradução de Italo Eugenio Mauro, vencedora do Prêmio Jabuti e celebrada por sua fidelidade à métrica e à rima originais. A edição traz ainda, como prefácio, um inspirado ensaio de Otto Maria Carpeaux.', '857326120', 696, 1321, 2, 13),
(16, 'Os Lusíadas', 'Este épico extraordinário não apenas narra os feitos heroicos dos navegadores lusitanos, mas também moldou a própria língua portuguesa, tornando-se um símbolo da cultura e da identidade do país.\r\nCom uma maestria única, o poeta Alexei Bueno nos presenteia com uma edição meticulosamente revisada e enriquecida por mais de 1.200 notas, que desvendam mitologias, nuances históricas e nuances linguísticas, sem interromper o fluir da narrativa.\r\nAlém disso, esta edição traz à luz as estrofes outrora omitidas, conferindo ao leitor uma visão ainda mais completa da grandiosidade de Camões.\r\nAs páginas ganham vida com ilustrações marcantes, transformando esta edição em um verdadeiro tesouro visual. Não apenas uma leitura, mas uma imersão profunda e enriquecedora na epopeia que forjou Portugal.', '8520942954', 696, 1572, 4, 17),
(17, 'Crime e castigo', 'Publicado em 1866, Crime e castigo é a obra mais célebre de Fiódor Dostoiévski. Neste livro, Raskólnikov, um jovem estudante, pobre e desesperado, perambula pelas ruas de São Petersburgo até cometer um crime que tentará justificar por uma teoria: grandes homens, como César ou Napoleão, foram assassinos absolvidos pela História. Este ato desencadeia uma narrativa labiríntica que arrasta o leitor por becos, tabernas e pequenos cômodos, povoados de personagens que lutam para preservar sua dignidade contra as várias formas da tirania. Esta é a primeira tradução direta da obra lançada no Brasil, e recebeu em 2002 o Prêmio Paulo Rónai de Tradução da Fundação Biblioteca Nacional.', '8573266465', 592, 1866, 3, 13),
(18, 'Hamlet', 'Neste clássico da literatura mundial, um jovem príncipe se reúne com o fantasma de seu pai, que alega que seu próprio irmão, agora casado com sua viúva, o assassinou. O príncipe cria um plano para testar a veracidade de tal acusação, forjando uma brutal loucura para traçar sua vingança. Mas sua aparente insanidade logo começa a causar estragos - para culpados e inocentes. Esta é a sinopse da tragédia de Shakespeare, agora em nova e fluente tradução de Lawrence Flores Pereira, que também oferece uma alentada introdução à obra. A edição traz ainda um clássico ensaio do poeta T.S. Eliot sobre o texto shakespeariano. Hamlet é um dos momentos mais altos da criação artística, um retrato - eletrizante e sempre contemporâneo - da complexa vida emocional de um ser humano.', '858285014', 320, 1623, 5, 3),
(19, 'A Metamorfose', 'A metamorfose é a mais célebre novela de Franz Kafka e uma das mais importantes de toda a história da literatura. Sem a menor cerimônia, o texto coloca o leitor diante de um caixeiro-viajante - o famoso Gregor Samsa - transformado em inseto monstruoso. A partir daí, a história é narrada com um realismo inesperado que associa o inverossímil e o senso de humor ao que é trágico, grotesco e cruel na condição humana - tudo no estilo transparente e perfeito desse mestre inconfundível da ficção universal.', '8571646856', 96, 1915, 4, 3),
(20, 'O cortiço', 'Aluísio Azevedo retrata as péssimas condições de vida dos moradores dos cortiços cariocas neste romance estrelado por dois imigrantes portugueses. A linguagem rebuscada do autor naturalista do século XIX é traduzida para os dias de hoje por meio das notas comentadas de Fátima Mesquita.', '8578886437', 304, 1890, 3, 18),
(21, 'O Navio Negreiro', 'Se Castro Alves fosse cantasse hoje seu poema O navio negreiro em praça pública, como seria? O rapper Slim Rimografia apresenta neste livro sua versão musicada do poema que marcou a história da literatura brasileira, por se tornar um ícone da denúncia das injustiças contra os negros. Ilustrado com grafites do Grupo Opni, e acompanhado de textos informativos do professor e doutor José Luis Solazzi, especialista em abolicionismo escravista e movimentos sociais, esta obra revela que a luta contra o preconceito e a defesa da cultura afro-brasileira é responsabilidade de todos nós.', '8578880307', 64, 1880, 3, 18),
(23, 'A Biblioteca da Meia-Noite', 'Aos 35 anos, Nora Seed é uma mulher cheia de talentos e poucas conquistas. Arrependida das escolhas que fez no passado, ela vive se perguntando o que poderia ter acontecido caso tivesse vivido de maneira diferente. Após ser demitida e seu gato ser atropelado, Nora vê pouco sentido em sua existência e decide colocar um ponto final em tudo. Porém, quando se vê na Biblioteca da Meia-Noite, Nora ganha uma oportunidade única de viver todas as vidas que poderia ter vivido.\r\n\r\nNeste lugar entre a vida e a morte, e graças à ajuda de uma velha amiga, Nora pode, finalmente, se mudar para a Austrália, reatar relacionamentos antigos – ou começar outros –, ser uma estrela do rock, uma glaciologista, uma nadadora olímpica... enfim, as opções são infinitas. Mas será que alguma dessas outras vidas é realmente melhor do que a que ela já tem?\r\n\r\nEm A Biblioteca da Meia-Noite , Nora Seed se vê exatamente na situação pela qual todos gostaríamos de poder passar: voltar no tempo e desfazer algo de que nos arrependemos. Diante dessa possibilidade, Nora faz um mergulho interior viajando pelos livros da Biblioteca da Meia-Noite até entender o que é verdadeiramente importante na vida e o que faz, de fato, com que ela valha a pena ser vivida.', '6558380544', 308, 2015, 5, 19),
(24, 'Olhai os Lírios do Campo', 'Primeiro best-seller de Erico Verissimo, Olhai os lírios do campo representou uma guinada na carreira literária do escritor. Várias edições se esgotaram em poucos meses. Segundo Erico, o sucesso foi tão grande que \"teve a força de arrastar consigo os romances\" que publicara antes em modestas tiragens. Eugênio Pontes, moço de origem humilde, a custo se forma médico e, graças a um casamento por interesse, ingressa na elite da sociedade. Nesse percurso, porém, é obrigado a virar as costas para a família, deixar de lado antigos ideais humanitários e abandonar a mulher que realmente ama. Sensível, comovente, Olhai os lírios do campo é um convite à reflexão sobre os valores autênticos da vida.', '8535906096', 288, 1938, 4, 3);

-- --------------------------------------------------------

--
-- Estrutura para tabela `livro_has_autor`
--

CREATE TABLE `livro_has_autor` (
  `fk_livro` int(11) DEFAULT NULL,
  `fk_autor` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `livro_has_autor`
--

INSERT INTO `livro_has_autor` (`fk_livro`, `fk_autor`) VALUES
(6, 3),
(7, 3),
(8, 3),
(9, 3),
(10, 3),
(11, 3),
(12, 3),
(13, 1),
(14, 1),
(15, 38),
(16, 39),
(17, 25),
(18, 22),
(19, 40),
(20, 11),
(21, 10),
(23, 42),
(24, 43);

-- --------------------------------------------------------

--
-- Estrutura para tabela `livro_has_categoria`
--

CREATE TABLE `livro_has_categoria` (
  `fk_livro` int(11) DEFAULT NULL,
  `fk_categoria` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `livro_has_categoria`
--

INSERT INTO `livro_has_categoria` (`fk_livro`, `fk_categoria`) VALUES
(6, 16),
(6, 9),
(7, 16),
(7, 9),
(8, 16),
(8, 9),
(9, 16),
(9, 9),
(10, 16),
(10, 9),
(11, 16),
(11, 9),
(12, 16),
(12, 9),
(13, 25),
(13, 3),
(14, 2),
(15, 6),
(16, 6),
(17, 3),
(18, 33),
(18, 34),
(19, 9),
(19, 14),
(20, 2),
(21, 6),
(23, 8),
(23, 29),
(24, 35);

-- --------------------------------------------------------

--
-- Estrutura para tabela `resenha`
--

CREATE TABLE `resenha` (
  `codResenha` int(11) NOT NULL,
  `privado` tinyint(1) NOT NULL,
  `dataCriacao` date NOT NULL,
  `dataModificacao` date NOT NULL,
  `resenha` longtext NOT NULL,
  `fk_livro` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `resenha`
--

INSERT INTO `resenha` (`codResenha`, `privado`, `dataCriacao`, `dataModificacao`, `resenha`, `fk_livro`) VALUES
(10, 1, '2025-11-23', '2025-11-23', 'Minha Resenha', NULL),
(13, 1, '2025-11-24', '0000-00-00', '', NULL),
(14, 1, '2025-11-24', '0000-00-00', '', NULL),
(16, 0, '2025-11-24', '2025-11-24', 'Uma das curiosidades da personalidade de Brás Cubas é que ele sonhava alcançar a fama pela invenção de um “emplasto” anti-hipocondríaco, destinado a aliviar a nossa melancólica humanidade.\r\n“Essa ideia nada mais era nada menos que a invenção de um medicamento sublime, um emplasto anti-hipocondríaco, destinado a aliviar a nossa melancólica humanidade. Na petição tive o privilégio que então redigi, chamei a atenção do governo para esse resultado, verdadeiramente cristão. Todavia, não neguei aos amigos as vantagens pecuniárias que devem resultar da distribuição de um produto de tamanhos efeitos. Agora, porém, que estou cá do outro lado da vida, posso confessar tudo: o que me influiu principalmente foi o gosto de ver impressas nos jornais, mostradores, folhetos, esquinas, e enfim nas caixinhas do remédio, estas três palavras: Emplasto Brás Cubas”. (pag. 3)\r\nConsegue apenas mais um fracasso para sua coleção de insucessos, ironizados na dedicatória do livro:\r\n “AO VERME QUE PRIMEIRO ROEU AS FRIAS CARNES DO MEU CADÁVER, DEDICO COMO SAUDOSA LEMBRANÇA ESTAS MEMÓRIAS”\r\nA característica mais marcante do estilo machadiano é a digressão. A narrativa de Machado de Assis é constantemente interrompida por comentários metalinguísticos, intertextualidades, histórias paralelas e, principalmente, análises filosóficas da sociedade e do indivíduo. Isso faz com que seus enredos fiquem sempre fragmentados e embaralhados. Essa dificuldade de leitura, no entanto, é compensada pelo humor inteligente e pela estrutura dinâmica e moderna de seus livros.', NULL),
(18, 1, '2025-11-24', '0000-00-00', '', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

CREATE TABLE `usuario` (
  `codUsuario` int(11) NOT NULL,
  `email` varchar(250) NOT NULL,
  `senha` varchar(60) NOT NULL,
  `nome` varchar(250) NOT NULL,
  `dtNascimento` date NOT NULL,
  `adm` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `usuario`
--

INSERT INTO `usuario` (`codUsuario`, `email`, `senha`, `nome`, `dtNascimento`, `adm`) VALUES
(1, 'usuarioAdmin@platleitura.com', '$2b$12$IYKIZRG8iLqwivy8gRUNYeqmf6fqn4sOrxDydlBsVLk87l7AfMQvi', 'Pedro', '2000-03-20', 1),
(2, 'usuarioTeste@platleitura.com', '$2y$10$QlHZln895yxz0ZXojZn3uOOr5cuzAkcnwpIBpQJg4r1Uv9vKl7eXa', 'Demostração', '2015-03-04', 0),
(8, 'teste@lectus.com', '$2y$10$0Aej/R6TOVBaPeOj5g9OnOKHNAvUdX6L11UMdorKtwA2vOg34l7ei', 'Teste', '2000-01-01', 0);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `autor`
--
ALTER TABLE `autor`
  ADD PRIMARY KEY (`codAutor`);

--
-- Índices de tabela `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`codCategoria`);

--
-- Índices de tabela `comentario`
--
ALTER TABLE `comentario`
  ADD PRIMARY KEY (`codComentario`),
  ADD KEY `fk_livro` (`fk_livro`),
  ADD KEY `fk_usuario` (`fk_usuario`);

--
-- Índices de tabela `edicao`
--
ALTER TABLE `edicao`
  ADD PRIMARY KEY (`codEdicao`),
  ADD KEY `fk_livro` (`fk_livro`);

--
-- Índices de tabela `editora`
--
ALTER TABLE `editora`
  ADD PRIMARY KEY (`codEditora`);

--
-- Índices de tabela `faixaetaria`
--
ALTER TABLE `faixaetaria`
  ADD PRIMARY KEY (`codFaixaEtaria`);

--
-- Índices de tabela `leitura`
--
ALTER TABLE `leitura`
  ADD PRIMARY KEY (`codLeitura`),
  ADD KEY `fk_livro` (`fk_livro`),
  ADD KEY `fk_resenha` (`fk_resenha`),
  ADD KEY `fk_usuario` (`fk_usuario`);

--
-- Índices de tabela `livro`
--
ALTER TABLE `livro`
  ADD PRIMARY KEY (`codLivro`),
  ADD KEY `fk_faixaEtaria` (`fk_faixaEtaria`),
  ADD KEY `fk_editora` (`fk_editora`);

--
-- Índices de tabela `livro_has_autor`
--
ALTER TABLE `livro_has_autor`
  ADD KEY `fk_livro` (`fk_livro`),
  ADD KEY `fk_autor` (`fk_autor`);

--
-- Índices de tabela `livro_has_categoria`
--
ALTER TABLE `livro_has_categoria`
  ADD KEY `fk_livro` (`fk_livro`),
  ADD KEY `fk_categoria` (`fk_categoria`);

--
-- Índices de tabela `resenha`
--
ALTER TABLE `resenha`
  ADD PRIMARY KEY (`codResenha`),
  ADD KEY `fk_livro` (`fk_livro`);

--
-- Índices de tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`codUsuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `autor`
--
ALTER TABLE `autor`
  MODIFY `codAutor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT de tabela `categoria`
--
ALTER TABLE `categoria`
  MODIFY `codCategoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de tabela `comentario`
--
ALTER TABLE `comentario`
  MODIFY `codComentario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `edicao`
--
ALTER TABLE `edicao`
  MODIFY `codEdicao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de tabela `editora`
--
ALTER TABLE `editora`
  MODIFY `codEditora` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de tabela `faixaetaria`
--
ALTER TABLE `faixaetaria`
  MODIFY `codFaixaEtaria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `leitura`
--
ALTER TABLE `leitura`
  MODIFY `codLeitura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT de tabela `livro`
--
ALTER TABLE `livro`
  MODIFY `codLivro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de tabela `resenha`
--
ALTER TABLE `resenha`
  MODIFY `codResenha` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `codUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `comentario`
--
ALTER TABLE `comentario`
  ADD CONSTRAINT `comentario_ibfk_1` FOREIGN KEY (`fk_livro`) REFERENCES `livro` (`codLivro`) ON UPDATE CASCADE,
  ADD CONSTRAINT `comentario_ibfk_2` FOREIGN KEY (`fk_usuario`) REFERENCES `usuario` (`codUsuario`) ON UPDATE CASCADE;

--
-- Restrições para tabelas `edicao`
--
ALTER TABLE `edicao`
  ADD CONSTRAINT `edicao_ibfk_1` FOREIGN KEY (`fk_livro`) REFERENCES `livro` (`codLivro`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `leitura`
--
ALTER TABLE `leitura`
  ADD CONSTRAINT `leitura_ibfk_1` FOREIGN KEY (`fk_livro`) REFERENCES `livro` (`codLivro`) ON UPDATE CASCADE,
  ADD CONSTRAINT `leitura_ibfk_2` FOREIGN KEY (`fk_resenha`) REFERENCES `resenha` (`codResenha`) ON UPDATE CASCADE,
  ADD CONSTRAINT `leitura_ibfk_3` FOREIGN KEY (`fk_usuario`) REFERENCES `usuario` (`codUsuario`) ON UPDATE CASCADE;

--
-- Restrições para tabelas `livro`
--
ALTER TABLE `livro`
  ADD CONSTRAINT `livro_ibfk_1` FOREIGN KEY (`fk_faixaEtaria`) REFERENCES `faixaetaria` (`codFaixaEtaria`) ON UPDATE CASCADE,
  ADD CONSTRAINT `livro_ibfk_2` FOREIGN KEY (`fk_editora`) REFERENCES `editora` (`codEditora`) ON UPDATE CASCADE;

--
-- Restrições para tabelas `livro_has_autor`
--
ALTER TABLE `livro_has_autor`
  ADD CONSTRAINT `livro_has_autor_ibfk_1` FOREIGN KEY (`fk_livro`) REFERENCES `livro` (`codLivro`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `livro_has_autor_ibfk_2` FOREIGN KEY (`fk_autor`) REFERENCES `autor` (`codAutor`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `livro_has_categoria`
--
ALTER TABLE `livro_has_categoria`
  ADD CONSTRAINT `livro_has_categoria_ibfk_1` FOREIGN KEY (`fk_livro`) REFERENCES `livro` (`codLivro`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `livro_has_categoria_ibfk_2` FOREIGN KEY (`fk_categoria`) REFERENCES `categoria` (`codCategoria`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `resenha`
--
ALTER TABLE `resenha`
  ADD CONSTRAINT `resenha_ibfk_1` FOREIGN KEY (`fk_livro`) REFERENCES `livro` (`codLivro`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

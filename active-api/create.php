<?php
$AdminLevel = LEVEL_WC_LIVES;
if (!APP_LIVES || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
endif;

// AUTO INSTANCE OBJECT READ
if (empty($Read)):
    $Read = new Read;
endif;

// AUTO INSTANCE OBJECT CREATE
if (empty($Create)):
    $Create = new Create;
endif;


   $leadLovers = new LeadLovers;


$LiveId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($LiveId):
    $Read->ExeRead(DB_LIVES, "WHERE live_id = :id", "id={$LiveId}");
    if ($Read->getResult()):
        $FormData = array_map('htmlspecialchars', $Read->getResult()[0]);
        extract($FormData);
    else:
        $_SESSION['trigger_controll'] = Erro("<b>OPPSS {$Admin['user_name']}</b>, você tentou editar uma live que não existe ou que foi removida recentemente!",
            E_USER_NOTICE);
        header('Location: dashboard.php?wc=lives/home');
    endif;
else:
    $LiveCreate = [
        'live_date' => date('Y-m-d H:i:s'),
        'live_end' => (LIVE_END_DATE == 1 ? date('Y-m-d H:i:s', strtotime('+' . LIVE_END_DAYS)) : null),
        'live_status' => 0
    ];
    $Create->ExeCreate(DB_LIVES, $LiveCreate);
    header('Location: dashboard.php?wc=lives/create&id=' . $Create->getResult());
endif;
?>
<header class="dashboard_header">
    <div class="dashboard_header_title">
        <h1 class="icon-video-camera"><?= $live_title ? $live_title : 'Nova Live'; ?></h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=lives/home">Galeria de Lives</a>
            <span class="crumb">/</span>
            Gerenciar Live
        </p>
    </div>

    <div class="dashboard_header_search">
        <a title="Painel de Controle" href="dashboard.php?wc=lives/statistic&id=<?= $live_id; ?>" class="btn btn_yellow icon-cogs">Painel de Controle</a>
        <?= (!empty($live_name) ? '<a target="_blank" title="Ver página da live!" href="' . LIVE_BASE . '/' . $live_name . '" class="wc_view btn btn_green icon-eye">Ver página da live!</a>' : null); ?>
    </div>
</header>

<div class="dashboard_content dashboard_users">
    <form name="live_add" action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="callback" value="Lives"/>
        <input type="hidden" name="callback_action" value="manage"/>
        <input type="hidden" name="live_id" value="<?= $LiveId; ?>"/>

        <div class="box box70">
            <!--CONFIGURAÇÕES DA LIVE-->
            <article class="wc_tab_target wc_active" id="config">
                <div class="panel_header default">
                    <h2 class="icon-paste">Dados da Live:</h2>
                </div>

                <div class="panel">
                    <label class="label">
                        <span class="legend">Capa da sala (Opcional):</span>
                        <input type="file" name="live_cover" class="wc_loadimage"/>
                    </label>

                    <label class="label">
                        <span class="legend">Título:</span>
                        <input style="font-size: 1.4em;" type="text" name="live_title" value="<?= $live_title; ?>" placeholder="Título da sala:" required/>
                    </label>

                    <label class="label">
                        <span class="legend">Breve Descrição:</span>
                        <textarea name="live_desc" rows="3" placeholder="Fale um pouco sobre a Live:" required><?= $live_desc; ?></textarea>
                    </label>

                    <?php if (LIVE_LINK_LIVES): ?>
                        <label class="label">
                            <span class="legend">Link Alternativo (Opcional):</span>
                            <input id="live_add" type="text" name="live_name" value="<?= $live_name; ?>" placeholder="Link da Página:"/>
                        </label>
                    <?php endif; ?>

                    <div class="label_50">
                        <label class="label">
                            <span class="legend icon-tv">Plataforma da Live:</span>
                            <select name="live_video_type" required>
                                <option selected disabled value="">Qual Plataforma o vídeo está?:</option>
                                <option value="0" <?= ($live_video_type == 0 ? 'selected="selected"' : ''); ?>>YouTube
                                </option>
                                <option value="1" <?= ($live_video_type == 1 ? 'selected="selected"' : ''); ?>>
                                    Facebook
                                </option>
                                <option value="2" <?= ($live_video_type == 2 ? 'selected="selected"' : ''); ?>>Vimeo
                                </option>
                            </select>
                        </label>

                        <label class="label">
                            <span class="legend icon-youtube">ID do Vídeo:</span>
                            <input type="text" name="live_video_id" value="<?= ($live_video_id ? $live_video_id : null); ?>" placeholder="ID do Vídeo:" required/>
                        </label>
                    </div>

                    <div class="label_50">
                        <label class="label">
                            <span class="legend">Durante a Live, a interação fica:</span>
                            <select name="live_interaction_active" required>
                                <option selected disabled value="">Interação Habilitada?</option>
                                <option value="1" <?= ($live_interaction_active == 1 ? 'selected="selected"' : ''); ?>>
                                    Habilitada
                                </option>
                                <option value="2" <?= ($live_interaction_active == 2 ? 'selected="selected"' : ''); ?>>
                                    Desabilitada
                                </option>
                            </select>
                        </label>

                        <label class="label">
                            <span class="legend">Código da Hotmart: <span class="icon-question icon-notext wc_tooltip"><span class="wc_tooltip_balloon">Obrigatório para que os dados de compra e do "Boas Vindas" sejam exibidas no Painel de Controle!</span></span></span>
                            <input type="text" name="live_offer_code" value="<?= ($live_offer_code ? $live_offer_code : null); ?>" placeholder="Código da Hotmart:"/>
                        </label>
                    </div>

                    <div class="label_50">
                        <label class="label">
                            <span class="legend">Data de ínicio da Live:</span>
                            <input type="text" data-timepicker="true" readonly="readonly" class="jwc_datepicker" name="live_start" value="<?= (!empty($live_start) ? date('d/m/Y H:i',
                                strtotime($live_start)) : date('d/m/Y H:i')); ?>" required/>
                        </label>

                        <label class="label">
                            <span class="legend">Data de expiração da Live: <span class="wc_tooltip icon-question"><span class="wc_tooltip_baallon">Caso não queira que a live expire é só deixar o campo em branco.</span></span></span>
                            <input type="text" data-timepicker="true" readonly="readonly" class="jwc_datepicker" name="live_end" value="<?= (!empty($live_end) ? date('d/m/Y H:i',
                                strtotime($live_end)) : null); ?>"/>
                        </label>
                    </div>
                    <div class="clear"></div>
                </div>
            </article>
            <!--FECHA CONFIGURAÇÕES DA LIVE-->

            <!--SEGURANÇA DA LIVE CLASS-->
            <article class="wc_tab_target ds_none" id="security">
                <div class="panel_header default">
                    <h2 class="icon-key">Segurança da sala:</h2>
                </div>

                <div class="panel">
                    <div class="label_50">
                        <label class="label">
                            <span class="legend">Autenticação da Live: <span class="wc_tooltip icon-question"><span class="wc_tooltip_baallon">Usuários do site ainda estão sujeitos a outros critérios como "Nível de usuário", "Código de Acesso" e requisitos de cursos.</span></span></span>
                            <select name="live_verification" required>
                                <option selected disabled value="">Selecione o Tipo de Autenticação:</option>
                                <option value="1" <?= ($live_verification == 1 ? 'selected="selected"' : ''); ?>>
                                    Capturar LEADS
                                </option>
                                <option value="2" <?= ($live_verification == 2 ? 'selected="selected"' : ''); ?>>Somente
                                    Alunos
                                </option>
                            </select>
                        </label>

                        <label class="label">
                            <span class="legend">Código de Acesso: <span class="wc_tooltip icon-question"><span class="wc_tooltip_baallon">Caso não queira que a live tenha código de acesso é só deixar o campo em branco.</span></span></span>
                            <input type="text" name="live_code" value="<?= $live_code; ?>" placeholder="Código de acesso a Live. Ex.: AXwp9z7y"/>
                        </label>
                    </div>

                    <h3 class="icon-envelop" style="font-size: 1em; font-weight: 700; padding: 5px 0; border-bottom: 1px dotted #EEEEEE; margin: 20px 0">LeadLovers:</h3>

                    <label class="label">
                        <span class="legend">Salvar LEADs: <span class="wc_tooltip icon-question"><span class="wc_tooltip_baallon">O Lead será salvo no banco de dados e onde mais você escolher aqui.</span></span></span>
                        <select class="j_leadlovers" name="live_verification_type" required>
                            <option selected disabled value="">Selecione onde salvar os LEADs:</option>
                            <option value="dataBase" <?= ($live_verification_type == "dataBase" ? 'selected="selected"' : ''); ?>>
                                Somente no Banco de Dados
                            </option>
                            <option  value="LeadLovers" <?= ($live_verification_type == "LeadLovers" ? 'selected="selected"' : ''); ?>>
                                Salvar também no LeadLovers
                            </option>
                        </select>
                    </label>
                 <?php 
                    $leadLovers->getMachines();
                   
                          var_dump($leadLovers->getCallback());
                    
                 ?>
                    <div class="label_50 j_leadlovers_target ds_none">
                        <label class="label">
                        <span class="legend">Tags: <span class="wc_tooltip icon-question"><span class="wc_tooltip_baallon">Tags no LeadLovers, para colocar mais de uma tag basta separar por virgula</span></span></span>
                            <input type="text" name="live_verification_tags" value="<?= $live_verification_tags; ?>" placeholder="Tags, ex.: live 1, live 2"/>
                        </label>
                        <label class="label">
                            <span class="legend">Máquina LeadLovers: </span>
                            <select name="live_machine" class=' jcustom-select jwc_combo' data-c='Lives' data-ca='sequecy_filter'>
                                <option selected value="">Selecione Uma Máquina:</option>
                                <?php
                                $leadLovers->getMachines();
                                if($leadLovers->getCallback()):
                                    foreach ($leadLovers->getCallback()->Items as $Value => $Desc):
                                        echo "<option";
                                        if ($Desc->MachineCode == $live_machine):
                                            echo " selected='selected'";
                                        endif;
                                        echo " value='{$Desc->MachineCode}'> {$Desc->MachineName}</option>";
                                    endforeach;
                                endif;
                                ?>
                            </select>
                        </label>
                      
                        <label class="label">
                            <span class="legend">Sequência de E-mail:</span>
                            <select name="live_sequecy" class="jwc_combo_target_sequecy jwc_combo" data-c='Lives' data-ca='class_select'>
                                <option value="">Selecione uma Sequência</option>
                                <?php
                                $leadLovers->getEmailSequency($live_machine);
                                foreach ($leadLovers->getCallback()->Items as $Value => $Desc):
                                    echo "<option";
                                    if ($Desc->SequenceCode == $live_sequecy):
                                        echo " selected='selected'";
                                    endif;
                                    echo " value='{$Desc->SequenceCode}'> {$Desc->SequenceName}</option>";
                                endforeach;
                                ?>
                            </select>
                        </label>
                    </div>

                    <h3 class="icon-lock" style="font-size: 1em; font-weight: 700; padding: 5px 0; border-bottom: 1px dotted #EEEEEE; margin: 20px 0">Restrito a Usuários:</h3>

                    <label class="label">
                        <span class="legend">Nível de usuário: <span class="wc_tooltip icon-question"><span class="wc_tooltip_baallon">Só funciona quando a "Autenticação da Live" é para "Somente Alunos".</span></span></span>
                        <select name="live_level">
                            <option selected value="">Livre para todos os níveis de usuário:</option>
                            <?php
                            $NivelDeAcesso = getWcLevel();
                            foreach ($NivelDeAcesso as $Nivel => $Desc):
                                echo "<option";
                                if ($Nivel == $live_level):
                                    echo " selected='selected'";
                                endif;
                                echo " value='{$Nivel}'>Apartir de {$Desc}</option>";
                            endforeach;
                            ?>
                        </select>
                    </label>

                    <?php
                    $Read->FullRead("SELECT course_id, course_title FROM " . DB_EAD_COURSES);
                    if ($Read->getResult()):
                        ?>
                        <div class="post_create_categories">
                            <span class="legend">Habilitar live apenas para os cursos:</span>
                            <?php
                            foreach ($Read->getResult() as $Cursos):
                                echo "<p class='post_create_cat'><label class='label_check'><input type='checkbox' name='live_courses[]' value='{$Cursos['course_id']}'";
                                if (in_array($Cursos['course_id'], explode(',', $live_courses))):
                                    echo " checked";
                                endif;
                                echo "> {$Cursos['course_title']}</label></p>";
                            endforeach;
                            ?>
                        </div>
                    <?php
                    endif;
                    ?>
                    <div class="clear"></div>
                </div>
            </article>
            <!--FECHA SEGURANÇA DA LIVE CLASS-->

            <!--BOTÃO DE OFERTA-->
            <article class="wc_tab_target ds_none" id="offer">
                <div class="panel_header default">
                    <h2 class="icon-price-tags">Botão de oferta:</h2>
                </div>

                <div class="panel">
                    <label class="label">
                        <span class="legend icon-price-tag">Link da Oferta:</span>
                        <input type="text" name="live_cta_link" value="<?= ($live_cta_link ? $live_cta_link : null); ?>" placeholder="Link da Oferta:"/>
                    </label>

                    <div class="label_50">
                        <label class="label">
                            <span class="legend">Oferta:</span>
                            <select name="live_cta" required>
                                <option selected disabled value="">Oferta Habilitada?:</option>
                                <option value="0" <?= ($live_cta == 0 ? 'selected="selected"' : ''); ?>>Desabilitada
                                </option>
                                <option value="1" <?= ($live_cta == 1 ? 'selected="selected"' : ''); ?>>Habilitada</option>
                            </select>
                        </label>

                        <label class="label">
                            <span class="legend">Texto da Oferta:</span>
                            <input type="text" name="live_cta_text" value="<?= ($live_cta_text ? $live_cta_text : null); ?>" placeholder="Texto da Oferta:"/>
                        </label>
                    </div>

                    <div class="label_50">
                        <label class="label">
                            <span class="legend">Cor do Botão:</span>
                            <select name="live_cta_color">
                                <option selected disabled value="">Selecione a cor</option>
                                <option <?= ($live_cta_color == 'blue' ? "selected='selected'" : ''); ?> value="blue">
                                    Azul
                                </option>
                                <option <?= ($live_cta_color == 'green' ? "selected='selected'" : ''); ?> value="green">
                                    Verde
                                </option>
                                <option <?= ($live_cta_color == 'yellow' ? "selected='selected'" : ''); ?> value="yellow">
                                    Amarelo
                                </option>
                                <option <?= ($live_cta_color == 'red' ? "selected='selected'" : ''); ?> value="red">
                                    Vermelho
                                </option>
                                <option <?= ($live_cta_color == 'orange' ? "selected='selected'" : ''); ?> value="orange">
                                    Laranja
                                </option>
                            </select>
                        </label>

                        <label class="label">
                            <span class="legend">Ícone da Oferta:</span>
                            <select name="live_cta_icon">
                                <option selected disabled value="">Selecione o ícone</option>
                                <option <?= ($live_cta_icon == 'icon-gift' ? "selected='selected'" : ''); ?> value="icon-gift">
                                    Caixa de Presente
                                </option>
                                <option <?= ($live_cta_icon == 'icon-credit-card' ? "selected='selected'" : ''); ?> value="icon-credit-card">
                                    Cartão de Crédito
                                </option>
                                <option <?= ($live_cta_icon == 'icon-cart' ? "selected='selected'" : ''); ?> value="icon-cart">
                                    Carrinho de Compra
                                </option>
                                <option <?= ($live_cta_icon == 'icon-heart' ? "selected='selected'" : ''); ?> value="icon-heart">
                                    Coração
                                </option>
                                <option <?= ($live_cta_icon == 'icon-download' ? "selected='selected'" : ''); ?> value="icon-download">
                                    Download
                                </option>
                                <option <?= ($live_cta_icon == 'icon-fire' ? "selected='selected'" : ''); ?> value="icon-fire">
                                    Fogo
                                </option>
                                <option <?= ($live_cta_icon == 'icon-rocket' ? "selected='selected'" : ''); ?> value="icon-rocket">
                                    Foguete
                                </option>
                                <option <?= ($live_cta_icon == 'icon-pencil2' ? "selected='selected'" : ''); ?> value="icon-pencil2">
                                    Lápis
                                </option>
                                <option <?= ($live_cta_icon == 'icon-book' ? "selected='selected'" : ''); ?> value="icon-book">
                                    Livro
                                </option>
                                <option <?= ($live_cta_icon == 'icon-clock' ? "selected='selected'" : ''); ?> value="icon-clock">
                                    Relógio
                                </option>
                            </select>
                        </label>
                    </div>
                    <div class="clear"></div>
                </div>
            </article>
            <!--FECHA BOTÃO DE OFERTA-->
        </div>


        <!--ENQUETES-->
        <div class="wc_tab_target ds_none" id="survey">
            <div class="panel_header default">
                <h2 class="icon-list-numbered">Enquetes:</h2>
            </div>

            <div class="panel">

            </div>
        </div>
        <!--FECHA ENQUETES-->

        <div class="box box30">
            <?php
            $Image = (file_exists("../uploads/{$live_cover}") && !is_dir("../uploads/{$live_cover}") ? "uploads/{$live_cover}" : 'admin/_img/no_image.jpg');
            ?>
            <img class="live_cover" style="width: 100%;" src="../tim.php?src=<?= $Image; ?>&w=400&h=200" alt="" title=""/>

            <div class="panel">
                <div class="box_conf_menu">
                    <a class='conf_menu wc_tab wc_active' href='#config'>Configurações da sala</a>
                    <a class='conf_menu wc_tab' href='#security'>Segurança da sala</a>
                    <a class='conf_menu wc_tab' href='#offer'>Botão de Oferta</a>
                    <!--<a class='conf_menu wc_tab' href='#survey'>Enquetes</a>-->
                </div>
                <div class="m_top">&nbsp;</div>
                <label class="label">
                    <span class="legend">Professor:</span>
                    <select name="live_author" required>
                        <option value="<?= $Admin['user_id']; ?>"><?= $Admin['user_name']; ?> <?= $Admin['user_lastname']; ?></option>
                        <?php
                        $Read->FullRead("SELECT user_id, user_name, user_lastname FROM " . DB_USERS . " WHERE user_level >= :lv AND user_id != :uid", "lv=8&uid={$Admin['user_id']}");
                        if ($Read->getResult()):
                            foreach ($Read->getResult() as $PostAuthors):
                                echo "<option";
                                if ($PostAuthors['user_id'] == $live_author):
                                    echo " selected='selected'";
                                endif;
                                echo " value='{$PostAuthors['user_id']}'>{$PostAuthors['user_name']} {$PostAuthors['user_lastname']}</option>";
                            endforeach;
                        endif;
                        ?>
                    </select>
                </label>
                <div class="m_top">&nbsp;</div>
                <div class="wc_actions" style="text-align: right; margin-bottom: 10px;">
                    <label class="label_check label_publish <?= ($live_status == 1 ? 'active' : ''); ?>"><input style="margin-top: -1px;" type="checkbox" value="1" name="live_status" <?= ($live_status == 1 ? 'checked' : ''); ?>>
                        Publicar Agora!</label>
                    <button name="public" value="1" class="btn btn_green icon-share">ATUALIZAR</button>
                    <img class="form_load none" style="margin-left: 10px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.gif"/>
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </form>
</div>
<script>
  var LeadLovers = $('.j_leadlovers').find(":selected").val();
  if( LeadLovers == 'LeadLovers'){
    $('.j_leadlovers_target').fadeIn(1000, function () { });
  }else{
    $('.j_leadlovers_target').fadeOut(1000, function () { });
  }
$('.j_leadlovers').on('change', function() {
    var leadLovers = $(this).find(":selected").val();
    if( leadLovers == 'LeadLovers'){
        $('.j_leadlovers_target').fadeIn(1000, function () { });
    }else{
        $('.j_leadlovers_target').fadeOut(1000, function () { });
    }
});
</script>
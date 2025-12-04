{*
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 *}
{* SEU HEADER ORIGINAL COM LINKS CSS E JS DO TEMA *}
<link href="{$urls.theme_assets}fonts/fontawesome/css/all.min.css" rel="stylesheet">
<link href="{$urls.theme_assets}fonts/lipis-flags/flag-icon.min.css" rel="stylesheet">
<link href="{$urls.theme_assets}libraries/lightslider/css/lightslider.css" rel="stylesheet">
<script type="text/javascript" src="{$urls.theme_assets}libraries/lightslider/js/lightslider.js" defer></script>
<script type="text/javascript" src="{$urls.theme_assets}libraries/lazyload/lazyload.min.js" defer></script>
{if $page.page_name == 'index' or $page.page_name == 'my-account'}
    <script type="text/javascript" src="{$urls.theme_assets}js/homepage_feat_prods.js" defer></script>
{/if}

<div class="container">
    <div class="row">
        <a id="button_gotop"></a>
    </div>
</div>

<footer id="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                {* Seu menu desktop, se houver *}
                {* <nav class="seu-menu-desktop"> ... </nav> *}

                {* Botão que ativa o menu mobile - colocado dentro de uma div flexível para alinhamento *}
                <div class="mobile-menu-trigger-wrapper">
                    <button id="mobile-menu-toggle" aria-label="Abrir menu lateral">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {* O Overlay de fundo *}
    <div id="mobile-menu-overlay"></div>

    {* O Menu Lateral Mobile *}
    <div id="mobile-sidebar-menu">
        <button id="close-mobile-menu" aria-label="Fechar menu">&times;</button>
        <ul class="mobile-menu-list">
            <li><a href="{$urls.pages.index}" title="Início">Início</a></li>
            <li><a href="{$urls.pages.stores}" title="Nossas Lojas">Nossas Lojas</a></li>
            <li><a href="{$urls.pages.contact}" title="Contato">Contato</a></li>
            <li><a href="{$urls.pages.sitemap}" title="Mapa do Site">Mapa do Site</a></li>
            
            {if isset($categories) && $categories|@count > 0}
                <li class="mobile-has-submenu">
                    <a href="#">Categorias <i class="fas fa-chevron-down"></i></a>
                    <ul class="mobile-submenu">
                        {foreach from=$categories item=category}
                            <li><a href="{$category.url}">{$category.name}</a></li>
                        {/foreach}
                    </ul>
                </li>
            {/if}
            {* Mais itens de menu, se precisar *}
        </ul>
    </div>

    {* SEUS BLOCOS ORIGINAIS DO FOOTER: COPYRIGHT, PAGAMENTOS, etc. *}
    <div class="container">
        <div class="row">
            {block name='hook_footer'}
                {hook h='displayFooter'}
            {/block}
        </div>
        <div class="row">
            {block name='hook_footer_after'}
                {hook h='displayFooterAfter'}
            {/block}
        </div>
        <div class="row">
            <div class="col-md-12 pc-copyrightblock">
                <p class="col-md-6 pc-copyright">
                    {block name='copyright_link'}
                        <a class="_blank" href="#" rel="nofollow">
                            {l s='%copyright% %year% - Ecommerce software by %prestashop%' sprintf=['%prestashop%' => 'PrestaShop™', '%year%' => 'Y'|date, '%copyright%' => '©'] d='Shop.Theme.Global'}
                        </a>
                    {/block}
                </p>
                <p class="col-md-6 pc-copyright-payements">
                    <a href="/content/paiement-securise" title="Paiement disponible" rel="nofollow">
                        <img alt="Cheque" width="60" height="35" class="col-md-1" src="{$urls.theme_assets}/img/footer/payments/cheque.jpg"/>
                        <img alt="Virement bancaire" width="60" height="35" class="col-md-1" src="{$urls.theme_assets}/img/footer/payments/virement-bancaire.jpg"/>
                        <img alt="Mandat cash" width="60" height="35" class="col-md-1" src="{$urls.theme_assets}/img/footer/payments/mandat-cash.jpg"/>
                        <img alt="CB" width="60" height="35" class="col-md-1" src="{$urls.theme_assets}/img/footer/payments/cb.jpg"/>
                        <img alt="American Express" width="60" height="35" class="col-md-1" src="{$urls.theme_assets}/img/footer/payments/american-express.jpg"/>
                        <img alt="Mastercard" width="60" height="35" class="col-md-1" src="{$urls.theme_assets}/img/footer/payments/mastercard.jpg"/>
                        <img alt="VISA" width="60" height="35" class="col-md-1" src="{$urls.theme_assets}/img/footer/payments/visa.jpg"/>
                        <img alt="Paypal" width="60" height="35" class="col-md-1" src="{$urls.theme_assets}/img/footer/payments/paypal.jpg"/>
                        <img alt="Hipay" width="60" height="35" class="col-md-1" src="{$urls.theme_assets}/img/footer/payments/hipay.jpg"/>          
                    </a>
                </p>
            </div>
        </div>
    </div>
    
    {* SEU MENU INFERIOR DE ÍCONES ORIGINAL *}
    <div class="pc_respbotmenu">
      <a href="#" id="menu-icon-old"> 
        <i class="fas fa-bars"></i>
        <br />
        <span>Menu</span>
      </a>
      <a href="/">
        <i class="fas fa-home"></i>
        <br />
        <span>Accueil</span>
      </a>
      <a href="/mon-compte">
        <i class="fas fa-user-circle"></i>
        <br />
        <span>Compte</span>
      </a>
      <a href="/panier">
        <i class="fas fa-shopping-cart"></i>
        <br />
        <span>Panier</span>
      </a>
    </div>
</footer>

<style>
/* Estilos para o botão que aparecerá no footer */
.mobile-menu-trigger-wrapper {
    display: flex; /* Usar flexbox para alinhar */
    justify-content: flex-end; /* Alinha o botão à direita */
    padding: 10px 15px; /* Ajuste o padding conforme necessário */
    background-color: #f0f0f0; /* Cor de fundo para visualizar o bloco, remova depois */
}

#mobile-menu-toggle {
    background: none;
    border: none;
    cursor: pointer;
    padding: 10px;
    z-index: 20;
    /* Adicione estas linhas para alinhar se precisar */
    display: block; /* Para que os spans dentro sejam centralizados */
    margin-left: auto; /* Alinha o botão à direita dentro do wrapper */
}

#mobile-menu-toggle span {
    display: block;
    width: 25px;
    height: 3px;
    background-color: #333; /* Cor do hamburguer (ajuste se seu footer for escuro) */
    margin: 5px 0;
    transition: all 0.3s ease;
}

/* Menu Lateral Mobile */
#mobile-sidebar-menu {
    position: fixed;
    top: 0;
    right: -300px; /* Começa fora da tela */
    width: 280px; /* Largura do menu lateral */
    height: 100%;
    background-color: #222; /* Cor de fundo do menu */
    padding: 20px;
    box-shadow: -5px 0 15px rgba(0,0,0,0.3);
    z-index: 1000; /* Garante que fique acima de tudo */
    transition: right 0.3s ease-in-out; /* Animação de deslizamento */
    overflow-y: auto; /* Adiciona scroll se o conteúdo for grande */
}

#mobile-sidebar-menu.open {
    right: 0; /* Desliza para dentro da tela */
}

#close-mobile-menu {
    background: none;
    border: none;
    color: #fff; /* Cor do botão fechar */
    font-size: 30px;
    position: absolute;
    top: 10px;
    right: 15px;
    cursor: pointer;
}

#mobile-sidebar-menu .mobile-menu-list {
    list-style: none;
    padding: 40px 0 0 0; /* Espaço para o botão fechar */
    margin: 0;
}

#mobile-sidebar-menu .mobile-menu-list li {
    margin-bottom: 10px;
}

#mobile-sidebar-menu .mobile-menu-list li a {
    color: #fff;
    text-decoration: none;
    padding: 10px 0;
    display: block;
    border-bottom: 1px solid #444; /* Separador */
}

#mobile-sidebar-menu .mobile-menu-list li a:hover {
    color: #007bff; /* Cor de hover */
}

/* Estilos para Submenus Mobile (Categorias) */
#mobile-sidebar-menu .mobile-submenu {
    list-style: none;
    padding: 10px 0 0 20px; /* Recuo para sub-itens */
    margin: 0;
    display: none; /* Oculto por padrão */
}

#mobile-sidebar-menu .mobile-has-submenu.active .mobile-submenu {
    display: block; /* Mostra o submenu quando o pai está ativo */
}

/* Estilo para o ícone do submenu */
#mobile-sidebar-menu .mobile-has-submenu a i {
    float: right;
    margin-top: 5px;
    transition: transform 0.3s ease;
}

#mobile-sidebar-menu .mobile-has-submenu.active a i {
    transform: rotate(180deg); /* Gira o ícone ao abrir */
}

/* Overlay de fundo */
#mobile-menu-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
    z-index: 999; /* Abaixo do menu, mas acima do conteúdo */
    display: none; /* Oculto por padrão */
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
}

#mobile-menu-overlay.active {
    display: block;
    opacity: 1;
}

/* Media Query para exibir o botão hamburguer apenas no mobile */
@media (max-width: 768px) {
    .mobile-menu-trigger-wrapper {
        display: flex; /* Torna o wrapper visível no mobile */
    }
    /* Se você tiver um menu desktop no footer que precise ser escondido, adicione aqui */
    /* .seu-menu-desktop-aqui { display: none; } */
}

/* Estilos do seu menu inferior de ícones (pc_respbotmenu) */
.pc_respbotmenu {
    display: flex;
    justify-content: space-around;
    align-items: center;
    background-color: #f8f8f8;
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    padding: 10px 0;
    box-shadow: 0 -2px 5px rgba(0,0,0,0.1);
    z-index: 99;
}
.pc_respbotmenu a {
    text-align: center;
    color: #333;
    text-decoration: none;
    font-size: 12px;
}
.pc_respbotmenu a i {
    display: block;
    font-size: 20px;
    margin-bottom: 5px;
}
</style>

<script>
    // Seus scripts existentes (lazyload, etc.)
    $(document).ready(function() {
        lazyload();
        console.log('jQuery document ready e lazyload executados.');
    });

    // Início do script do menu mobile
    // Usando uma função auto-executável para isolar o escopo e garantir o DOMContentLoaded
    (function() {
        console.log('Script do menu mobile iniciado.');

        // Seletores atualizados para usar IDs
        const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
        const mobileSidebarMenu = document.getElementById('mobile-sidebar-menu');
        const closeMobileMenu = document.getElementById('close-mobile-menu');
        const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');
        const mobileHasSubmenus = document.querySelectorAll('#mobile-sidebar-menu .mobile-has-submenu > a');

        // Adicionando logs para verificar se os elementos foram encontrados
        console.log('mobileMenuToggle:', mobileMenuToggle);
        console.log('mobileSidebarMenu:', mobileSidebarMenu);
        console.log('closeMobileMenu:', closeMobileMenu);
        console.log('mobileMenuOverlay:', mobileMenuOverlay);
        console.log('mobileHasSubmenus (quantidade):', mobileHasSubmenus.length);


        // Função para abrir o menu mobile
        function openMobileMenu() {
            console.log('openMobileMenu() chamado.');
            if (mobileSidebarMenu && mobileMenuOverlay) {
                mobileSidebarMenu.classList.add('open');
                mobileMenuOverlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            } else {
                console.warn('Erro: Elementos do menu lateral não encontrados para abrir.');
            }
        }

        // Função para fechar o menu mobile
        function closeMobileMenuFunc() {
            console.log('closeMobileMenuFunc() chamado.');
            if (mobileSidebarMenu && mobileMenuOverlay) {
                mobileSidebarMenu.classList.remove('open');
                mobileMenuOverlay.classList.remove('active');
                document.body.style.overflow = '';
                
                document.querySelectorAll('#mobile-sidebar-menu .mobile-has-submenu.active').forEach(function(item) {
                    item.classList.remove('active');
                    const submenu = item.querySelector('.mobile-submenu');
                    if (submenu) {
                        submenu.style.display = 'none';
                    }
                });
            } else {
                console.warn('Erro: Elementos do menu lateral não encontrados para fechar.');
            }
        }

        // Eventos para o menu lateral
        if (mobileMenuToggle) {
            mobileMenuToggle.addEventListener('click', function(e) {
                console.log('Clique no mobileMenuToggle detectado!');
                e.preventDefault(); // MUITO IMPORTANTE: Previne o comportamento padrão
                openMobileMenu();
            });
        }

        if (closeMobileMenu) {
            closeMobileMenu.addEventListener('click', function(e) {
                console.log('Clique no closeMobileMenu detectado!');
                e.preventDefault();
                closeMobileMenuFunc();
            });
        }

        if (mobileMenuOverlay) {
            mobileMenuOverlay.addEventListener('click', function(e) {
                console.log('Clique no mobileMenuOverlay detectado!');
                e.preventDefault();
                closeMobileMenuFunc();
            });
        }

        // Evento para abrir/fechar submenus no mobile
        mobileHasSubmenus.forEach(function(item) {
            item.addEventListener('click', function(e) {
                console.log('Clique em submenu mobile detectado!');
                e.preventDefault();
                const parentLi = this.closest('li');
                
                document.querySelectorAll('#mobile-sidebar-menu .mobile-has-submenu.active').forEach(function(otherItem) {
                    if (otherItem !== parentLi) {
                        otherItem.classList.remove('active');
                        const otherSubmenu = otherItem.querySelector('.mobile-submenu');
                        if (otherSubmenu) {
                            otherSubmenu.style.display = 'none';
                        }
                    }
                });

                parentLi.classList.toggle('active');
                const submenu = parentLi.querySelector('.mobile-submenu');
                if (submenu) {
                    if (submenu.style.display === 'block') {
                        submenu.style.display = 'none';
                    } else {
                        submenu.style.display = 'block';
                    }
                }
            });
        });

    })(); // Fim da função auto-executável
</script>
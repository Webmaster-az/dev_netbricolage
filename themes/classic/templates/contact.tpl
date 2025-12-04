{**
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
{extends file='page.tpl'}
{block name='page_header_container'}{/block}
{block name="left_column"}
  <style>
    .contact_page_wrapper {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      grid-template-rows: 1fr;
      grid-column-gap: 24px;
      grid-row-gap: 24px;
    }

    .num_azur_container {
      width: 75%;
      margin: 0 auto;
    }

    .contact_page_block .num_azur_container p.num_azur {
      background: #019fe0;
      color: #fff;
      text-align: center;
      font-size: 26px;
      line-height: normal;
      margin: 0;
    }

    .contact_page_block .num_azur_container p {
      font-size: 8px;
      text-align: right;
      margin: 0 0 6px;
    }

    p.contact_email_box {
      font-weight: 700;
      font-size: 16px;
    }

    .contact_feature_container {
      margin: 32px 0;
    }

    ul.contact_feature_list {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      grid-template-rows: 1fr;
      grid-column-gap: 12px;
      grid-row-gap: 12px;
    }

    li.contact_feature_list_item {
      display: flex;
      align-items: flex-end;
      justify-content: flex-start;
      gap: 6px;
    }

    li.contact_feature_list_item img {
      width: 40px;
      height: 40px;
      object-fit: contain;
    }

    li.contact_feature_list_item div {
      line-height: normal;
    }

    li.contact_feature_list_item div p {
      font-size: 10px;
      margin: 0;
    }

    li.contact_feature_list_item div p:nth-child(1) {
      font-weight: 700;
      font-size: 12px;
      margin: 0;
    }

    .contacts_big_block_wrapper {
      display: flex;
    }

    .contacts_big_block_wrapper .contacts_big_block_text,
    .contacts_big_block_wrapper .contacts_big_block_image {
      flex: 50%;
    }

    img.contact_big_image {
      width: 100%;
    }

    .contact_page_block #content-wrapper {
      width: 100%;
      padding: 0;
    }

    .contact_page_block section#content,
    .contact_page_block section#content section.contact-form {
      padding: 0;
      border: 0;
    }


    @media screen and (max-width: 767px) {
      .contact_page_wrapper {
        grid-template-columns: repeat(1, 1fr);
      }

      li.contact_feature_list_item {
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
        text-align: center;
      }
    }
  </style>

  <div class="contact_page_container">
    <div class="contact_page_wrapper">
      <div class="contact_page_block">
        <h2>
          SERVICE COMMERCIAL
        </h2>
        <p><strong>Monsieur Manuel da Silva</strong> est à votre disposition:</p>
        <div class="num_azur_container">
          <p class="num_azur">
            <span>Nº AZUR</span> 0 811 560 973
          </p>
          <p>PRIX APPEL 0,06 €/min | Horaire: Lundi au vendredi 09:30H-13:00H - 14:30H-19:00H</p>
        </div>
        <p class="contact_email_box">Pour toute question : <a href="mailto:commercial@netbricolage.com"
            style="padding-left:10px;">commercial@netbricolage.com</a></p>
        <div class="contact_feature_container">
          <ul class="contact_feature_list">
            <li class="contact_feature_list_item">
              <img src="/img/icones/avatar_1716943.png" alt="Écoute">
              <div>
                <p>ÉCOUTE</p>
                <p>Une équipe disponible et réactive</p>
              </div>
            </li>
            <li class="contact_feature_list_item">
              <img src="/img/icones/commerce-shopping_1716933.png" alt="Conseil">
              <div>
                <p>CONSEIL</p>
                <p>Accompagnement personnalisé</p>
              </div>
            </li>
            <li class="contact_feature_list_item">
              <img src="/img/icones/book_1716821.png" alt="Devis">
              <div>
                <p>DEVIS</p>
                <p>Offres adaptées à vos besoins</p>
              </div>
            </li>
          </ul>
        </div>
      </div>

      <div class="contact_page_block">
        <h2>
          SERVICE APRÈS-VENTE
        </h2>
        <p><strong>Madame Elisabete Couto</strong> est à votre disposition:</p>
        <div class="num_azur_container">
          <p class="num_azur">
            <span>Nº AZUR</span> 0 811 560 973
          </p>
          <p>PRIX APPEL 0,06 €/min | Horaire: Lundi au vendredi 09:30H-13:00H - 14:30H-19:00H</p>
        </div>
        <p class="contact_email_box">Pour toute question : <a href="mailto:sav@azhabitacao.com"
            style="padding-left:10px;">sav@azhabitacao.com</a></p>
        <div class="contact_feature_container">
          <ul class="contact_feature_list">
            <li class="contact_feature_list_item">
              <img src="/img/icones/construction-tools_1716749.png" alt="Support">
              <div>
                <p>SUPPORT</p>
                <p>Assistance après l'achat</p>
            </div>
          </li>
          <li class="contact_feature_list_item">
            <img src="/img/icones/avatar_1716937.png" alt="Assistance">
            <div>
              <p>ASSISTANCE</p>
              <p>Support & suivi colis</p>
            </div>
          </li>
          <li class="contact_feature_list_item">
            <img src="/img/icones/bolt_1716745.png" alt="Garantie">
            <div>
              <p>GARANTIE</p>
              <p>Suivi, réparations ou échanges</p>
            </div>
          </li>
        </ul>
      </div>
    </div>

    <div class="contact_page_block contacts_big_block">
      <h2>
        SIÈGE SOCIAL ET COMPTABILITÉ
      </h2>
      <div class="contacts_big_block_wrapper">
        <div class="contacts_big_block_text">
          <p>Bonuspódio Unipessoal, LDA</p>
          <p>1480, Avenida Jorge Reis</p>
          <p>4760-692 Outiz</p>
          <p>VILA NOVA FAMALICÃO / PORTUGAL</p>
          <p>Tél. : +351 252 311 693</p>
        </div>
        <div class="contacts_big_block_image">
          <img src="/img/icones/2106.q707.022.S.m004.c10.warehouse isometric.jpg" class="contact_big_image"
            alt="Siège Social">
        </div>
      </div>
    </div>

    <div class="contact_page_block">
      {/block}
      {block name='page_content'}
      {widget name="contactform"}
      {/block}
    </div>

  </div>
</div>

{* <div class="col-md-12 pc-contactinfoblocks">
    <div class="col-lg-6">
      <h2>SERVICE COMMERCIAL</h2>
      <div>
        <span style="font-size: 12px;">
          Monsieur <strong>Pedro CAMPOS</strong> est à votre disposition :
        </span>
        <div class="col-md-12" style="display: flex;flex-direction: column;align-items: center;">
          <div class="pc-bluenrect">
            <span>
              Nº Azur
            </span>
            0 811 560 973
          </div>
          <span class="pc-localpriceandtime">
            PRIX APPEL 0,06 €/min | Horaire: Lundi au vendredi 10H-13:30H - 15H-20H
          </span>
        </div>
        <span class="pc-contactmailblk">
          Pour toute question : <a href="mailto:commercial@netbricolage.com"
            style="padding-left:10px;">commercial@netbricolage.com</a>
        </span>

        <div style="display:flex; justify-content:space-between; margin-top:15px; text-align:center; gap:10px;"
          class="contacts_icon_list">
          <div style="flex:1;" class="contacts_icon_list_item">
            <img src="/img/icones/avatar_1716943.png" alt="Écoute" style="width:40px; height:40px;">
            <div>
              <div style="margin-top:6px; font-weight:bold; font-size:13px;">ÉCOUTE</div>
              <div style="font-size:11px; color:#555;">Une équipe disponible et réactive</div>
            </div>
          </div>
          <div style="flex:1;" class="contacts_icon_list_item">
            <img src="/img/icones/commerce-shopping_1716933.png" alt="Conseil" style="width:40px; height:40px;">
            <div>
              <div style="margin-top:6px; font-weight:bold; font-size:13px;">CONSEIL</div>
              <div style="font-size:11px; color:#555;">Accompagnement personnalisé</div>
            </div>
          </div>
          <div style="flex:1;" class="contacts_icon_list_item">
            <img src="/img/icones/book_1716821.png" alt="Devis" style="width:40px; height:40px;">
            <div>
              <div style="margin-top:6px; font-weight:bold; font-size:13px;">DEVIS</div>
              <div style="font-size:11px; color:#555;">Offres adaptées à vos besoins</div>
            </div>
          </div>
        </div>
      </div>
  </div> *}



{* <div class="col-lg-6">
      <h2>SERVICE APRÈS-VENTE</h2>
      <div>
        <span style="font-size: 12px;">
          Mme <strong>Elisabete COUTO</strong> est à votre disposition :
        </span>
        <div class="col-md-12" style="display: flex;flex-direction: column;align-items: center;">
          <div class="pc-bluenrect">
            Nº Azur
            0 811 560 973
          </div>
          <span class="pc-localpriceandtime">
            PRIX APPEL 0,06 €/min | Horaire: Lundi au vendredi 10H-13:30H - 15H-20H
          </span>
        </div>
        <span class="pc-contactmailblk">
          Pour toute question : <a href="mailto:sav@azhabitacao.com" style="padding-left:10px;">sav@azhabitacao.com</a>
        </span>

        <div style="display:flex; justify-content:between; margin-top:15px; text-align:center; gap:10px;"
          class="contacts_icon_list">
          <div style="flex:1;" class="contacts_icon_list_item">
            <img src="/img/icones/construction-tools_1716749.png" alt=Support style="width:40px; height:40px;">
            <div>
              <div style="margin-top:6px; font-weight:bold; font-size:13px;">SUPPORT</div>
              <div style="font-size:11px; color:#555;">Assistance après l'achat</div>
                            </div>
                          </div>
                          <div style="flex:1;" class="contacts_icon_list_item">
                            <img src="/img/icones/avatar_1716937.png" alt="Assistance" style="width:40px; height:40px;">
                            <div>
                              <div style="margin-top:6px; font-weight:bold; font-size:13px;">ASSISTANCE</div>
                              <div style="font-size:11px; color:#555;">Support & suivi colis</div>
                            </div>
                          </div>
                          <div style="flex:1;" class="contacts_icon_list_item">
                            <img src="/img/icones/bolt_1716745.png" alt="Garantie" style="width:40px; height:40px;">
                            <div>
                              <div style="margin-top:6px; font-weight:bold; font-size:13px;">GARANTIE</div>
                              <div style="font-size:11px; color:#555;">Suivi, réparations ou échanges</div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div> *}



  {* <div class="col-lg-6">
    <h2>SIÈGE SOCIAL ET COMPTABILITÉ</h2>
    <div style="display:flex; justify-content:space-between; align-items:flex-start;">
      <div>
        <p style="font-size: 12px;">
          Bonuspódio Unipessoal, LDA
        </p>
        <p style="font-size: 12px;">
          1480, Avenida Jorge Reis
        </p>
        <p style="font-size: 12px;">
          4760-692 Outiz
        </p>
        <p style="font-size: 12px;">
          VILA NOVA FAMALICÃO / PORTUGAL
        </p>
        <p style="font-size: 12px;">
          Tél. : +351 252 311 693
        </p>
      </div>

      <!-- imagem -->
      <img src="/img/icones/2106.q707.022.S.m004.c10.warehouse isometric.jpg" class="contact_big_image"
        alt="Siège Social">

    </div>
  </div> *}
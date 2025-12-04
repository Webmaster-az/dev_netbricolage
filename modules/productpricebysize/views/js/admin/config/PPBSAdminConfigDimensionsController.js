/*
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author Musaffar Patel <musaffar.patel@gmail.com>
*  @copyright  2015-2021 Musaffar
*  @version  Release: $Revision$
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Property of Musaffar Patel
*/

PPBSAdminConfigDimensionsController = function(wrapper) {
	var self = this;
	self.wrapper = wrapper;
	self.$wrapper = $(wrapper);

	self.btn_image_remove = '.btn-image-remove';

    /**
     * on image sel;ect button click
     * @param $sender
     */
	self.onImageSelect = function($sender) {
        let rfm_url = admin_url + '/filemanager/dialog.php?type=1&field_id=file1';
        let input_id = $sender.attr('data-for');
        let id_lang = $sender.attr('data-id_lang');

        $.fancybox.open({
            href: rfm_url,
            width: 900,
            height: 600,
            type: 'iframe',
            autoScale: false,
            autoSize: true,
            wrapCSS: 'ppbs-filemanager',
            afterShow: function (instance, current) {
                var iFrameDOM = $("iframe.fancybox-iframe").contents();
                var iframeWindow = $("iframe.fancybox-iframe").eq(0)[0].contentWindow;
                iframeWindow.$("ul.grid").off('click', '.link');
                iframeWindow.$("ul.grid").find(".link").attr('data-field_id', input_id);

                iFrameDOM.find("ul.grid").on('click', '.link', function () {
                    var _this = $(this);
                    iframeWindow.window[_this.attr('data-function')](_this.attr('data-file'), _this.attr('data-field_id'));
                });
            },
            afterClose: function () {
                let image_url = $("#" + input_id).val();
                if (image_url != '') {
                    self.setImagePreview(image_url, id_lang);
                    self.buttonImageRemoveToggle(true, id_lang);
                }
            }
        });
    };

    /**
     * set the value of a field image (language specific)
     * @param value
     * @param id_lang
     */
	self.setImageField = function(value, id_lang) {
        $("input#image_" + id_lang).val(value);
    };

    /**
     * set the image source of the preview
     * @param src
     * @param id_lang
     */
	self.setImagePreview = function(src, id_lang) {
        let $image = $("#image_preview_" + id_lang);
        if (src !== '') {
            $image.attr('src', src);
            $image.show();
        } else {
            $image.hide();
        }
    };

    /**
     * show or hide the image remove button
     * @param visible
     * @param id_lang
     */
	self.buttonImageRemoveToggle = function(visible, id_lang) {
        let $btn_remove = $("#btn-image-remove-" + id_lang);

        if (visible === true) {
            $btn_remove.show();
        } else {
            $btn_remove.hide();
        }
    }

    /**
     * on image remove click
     * @param $sender
     */
	self.onButtonImageRemoveClick = function($sender) {
	    let id_lang = $sender.attr('data-id_lang');
	    self.setImageField('', id_lang);
	    self.setImagePreview('', id_lang);
        self.buttonImageRemoveToggle(false, id_lang);
    }

	/* function render main form into the tab canvas */
	self.render = function(id_ppbs_dimension) {

		if (id_ppbs_dimension == null)
			id_ppbs_dimension = 0;

		MPTools.waitStart();
		$.ajax({
			type: 'POST',
			url: module_config_url + '&route=ppbsadminconfigdimensionscontroller&action=render',
			async: true,
			cache: false,
			data: {
				'id_ppbs_dimension' : id_ppbs_dimension
			},
			success: function (html_content) {
				self.$wrapper.html(html_content);
				MPTools.waitEnd();
			}
		});
	};

	self.processForm = function() {
		MPTools.waitStart();
		$.ajax({
			type: 'POST',
			url: module_config_url + '&route=ppbsadminconfigdimensionscontroller&action=processform',
			async: true,
			cache: false,
			//dataType: "json",
			data: self.$wrapper.find(" :input, select").serialize(),
			success: function (jsonData) {
				self.render();
				MPTools.waitEnd();
				$.growl.notice({message: 'Dimension saved'});
			}
		});
	};

	/**
	 * Delete a dimenion
	 */
	self.processDelete = function(id) {
		if (!confirm('Are you sure you want to completely delete this dimension?')) return false;

		MPTools.waitStart();

		$.ajax({
			type: 'POST',
			url: module_config_url + '&route=ppbsadminconfigdimensionscontroller&action=processdelete',
			async: true,
			cache: false,
			//dataType: "json",
			data: {
				'id_ppbs_dimension' : id
			},
			success: function (jsonData) {
				self.render();
				MPTools.waitEnd();
			}
		});
	};

	self.init = function() {
		self.render();
	};
	self.init();

	/* Events */

	$("body").on("click", "#ppbs-dimension-save", function() {
		self.processForm();
		return false;
	});

	/**
	 * edit dimension icon click
	 */
	$("body").on("click", ".ppbs-dimension-edit", function() {
		var id = $(this).parents("tr").attr("data-id");
		self.render(id);
		return false;
	});

	/**
	 * delete dimension icon click
	 */
	$("body").on("click", ".ppbs-dimension-delete", function() {
		var id = $(this).parents("tr").attr("data-id");
		self.processDelete(id);
		return false;
	});

    /**
     * on image select
     */
    $("body").on("click", ".btn-image-select", function () {
        self.onImageSelect($(this));
        return false;
    });

    /**
     * on image select
     */
    $("body").on("click", self.btn_image_remove, function () {
        self.onButtonImageRemoveClick($(this));
        return false;
    });
};


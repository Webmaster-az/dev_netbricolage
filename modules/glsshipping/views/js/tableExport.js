/*The MIT License (MIT)

Copyright (c) 2014 https://github.com/kayalshri/

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.*/

(function($){
        $.fn.extend({
            tableExport: function(options) {
                var defaults = {
						separator: ',',
						ignoreColumn: [],
						tableName:'yourTableName',
						type:'csv',
						pdfFontSize:14,
						pdfLeftMargin:20,
						escape:'true',
						htmlContent:'false',
						consoleLog:'false'
				};
                
				var options = $.extend(defaults, options);
				var el = this;
				
				if(defaults.type == 'csv' || defaults.type == 'txt'){
				
					// Header
					var tdData ="";
					$(el).find('thead').find('tr').each(function() {
					tdData += "\n";					
						$(this).filter(':visible').find('th').each(function(index,data) {
							if ($(this).css('display') != 'none'){
								if(defaults.ignoreColumn.indexOf(index) == -1){
									tdData += '"' + parseString($(this)) + '"' + defaults.separator;									
								}
							}
							
						});
						tdData = $.trim(tdData);
						tdData = $.trim(tdData).substring(0, tdData.length -1);
					});
					
					// Row vs Column
					$(el).find('tbody').find('tr').each(function() {
					tdData += "\n";
						$(this).filter(':visible').find('td').each(function(index,data) {
							if ($(this).css('display') != 'none'){
								if(defaults.ignoreColumn.indexOf(index) == -1){
									tdData += '"'+ parseString($(this)) + '"'+ defaults.separator;
								}
							}
						});
						//tdData = $.trim(tdData);
						tdData = $.trim(tdData).substring(0, tdData.length -1);
					});
					
					//output
					if(defaults.consoleLog == 'true'){
						console.log(tdData);
					}
					var base64data = "base64," + $.base64.encode(tdData);
					/* window.open('data:application/'+defaults.type+';filename=exportData;' + base64data); */
					
					var blob = new Blob([tdData], { type: "application/csv" })
					window.saveAs(blob, 'exportData.csv');
					
					
				}else if(defaults.type == 'sql'){
				
					// Header
					var tdData ="INSERT INTO `"+defaults.tableName+"` (";
					$(el).find('thead').find('tr').each(function() {
					
						$(this).filter(':visible').find('th').each(function(index,data) {
							if ($(this).css('display') != 'none'){
								if(defaults.ignoreColumn.indexOf(index) == -1){
									tdData += '`' + parseString($(this)) + '`,' ;									
								}
							}
							
						});
						tdData = $.trim(tdData);
						tdData = $.trim(tdData).substring(0, tdData.length -1);
					});
					tdData += ") VALUES ";
					// Row vs Column
					$(el).find('tbody').find('tr').each(function() {
					tdData += "(";
						$(this).filter(':visible').find('td').each(function(index,data) {
							if ($(this).css('display') != 'none'){
								if(defaults.ignoreColumn.indexOf(index) == -1){
									tdData += '"'+ parseString($(this)) + '",';
								}
							}
						});
						
						tdData = $.trim(tdData).substring(0, tdData.length -1);
						tdData += "),";
					});
					tdData = $.trim(tdData).substring(0, tdData.length -1);
					tdData += ";";
					
					//output
					//console.log(tdData);
					
					if(defaults.consoleLog == 'true'){
						console.log(tdData);
					}
					
					var base64data = "base64," + $.base64.encode(tdData);
					window.open('data:application/sql;filename=exportData;' + base64data);
					
				
				}else if(defaults.type == 'json'){
				
					var jsonHeaderArray = [];
					$(el).find('thead').find('tr').each(function() {
						var tdData ="";	
						var jsonArrayTd = [];
					
						$(this).filter(':visible').find('th').each(function(index,data) {
							if ($(this).css('display') != 'none'){
								if(defaults.ignoreColumn.indexOf(index) == -1){
									jsonArrayTd.push(parseString($(this)));									
								}
							}
						});									
						jsonHeaderArray.push(jsonArrayTd);						
						
					});
					
					var jsonArray = [];
					$(el).find('tbody').find('tr').each(function() {
						var tdData ="";	
						var jsonArrayTd = [];
					
						$(this).filter(':visible').find('td').each(function(index,data) {
							if ($(this).css('display') != 'none'){
								if(defaults.ignoreColumn.indexOf(index) == -1){
									jsonArrayTd.push(parseString($(this)));									
								}
							}
						});									
						jsonArray.push(jsonArrayTd);									
						
					});
					
					var jsonExportArray =[];
					jsonExportArray.push({header:jsonHeaderArray,data:jsonArray});
					
					//Return as JSON
					//console.log(JSON.stringify(jsonExportArray));
					
					//Return as Array
					//console.log(jsonExportArray);
					if(defaults.consoleLog == 'true'){
						console.log(JSON.stringify(jsonExportArray));
					}
					var base64data = "base64," + $.base64.encode(JSON.stringify(jsonExportArray));
					window.open('data:application/json;filename=exportData;' + base64data);
				}else if(defaults.type == 'xml'){
				
					var xml = '<?xml version="1.0" encoding="utf-8"?>';
					xml += '<tabledata><fields>';

					// Header
					$(el).find('thead').find('tr').each(function() {
						$(this).filter(':visible').find('th').each(function(index,data) {
							if ($(this).css('display') != 'none'){					
								if(defaults.ignoreColumn.indexOf(index) == -1){
									xml += "<field>" + parseString($(this)) + "</field>";
								}
							}
						});									
					});					
					xml += '</fields><data>';
					
					// Row Vs Column
					var rowCount=1;
					$(el).find('tbody').find('tr').each(function() {
						xml += '<row id="'+rowCount+'">';
						var colCount=0;
						$(this).filter(':visible').find('td').each(function(index,data) {
							if ($(this).css('display') != 'none'){	
								if(defaults.ignoreColumn.indexOf(index) == -1){
									xml += "<column-"+colCount+">"+parseString($(this))+"</column-"+colCount+">";
								}
							}
							colCount++;
						});															
						rowCount++;
						xml += '</row>';
					});					
					xml += '</data></tabledata>'
					
					if(defaults.consoleLog == 'true'){
						console.log(xml);
					}
					
					var base64data = "base64," + $.base64.encode(xml);
					window.open('data:application/xml;filename=exportData;' + base64data);

				}else if(defaults.type == 'excel' || defaults.type == 'doc'|| defaults.type == 'powerpoint'  ){
					//console.log($(this).html());
					var excel="<table>";
					// Header
					var content = '';
					$(el).find('thead').find('tr').each(function() {
						excel += "<tr>";
						$(this).filter(':visible').find('th').each(function(index,data) {
							if ($(this).css('display') != 'none'){					
								if(defaults.ignoreColumn.indexOf(index) == -1){
									excel += "<td>" + parseString($(this))+ "</td>";
								}
							}
						});	
						excel += '</tr>';						
						
					});					
					
					
					// Row Vs Column
					var rowCount=1;
					$(el).find('tbody').find('tr').each(function() {
						excel += "<tr>";
						var colCount=0;
						$(this).filter(':visible').find('td').each(function(index,data) {
							if ($(this).css('display') != 'none'){	
								if(defaults.ignoreColumn.indexOf(index) == -1){
									

									excel += "<td>"+parseString($(this))+"</td>";
								}
							}
							colCount++;
						});															
						rowCount++; 
						excel += '</tr>';
					});					
					excel += '</table>'
					
					if(defaults.consoleLog == 'true'){
						console.log(excel);
					}
					
					var excelFile = "<html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:x='urn:schemas-microsoft-com:office:"+defaults.type+"' xmlns='http://www.w3.org/TR/REC-html40'>";
					excelFile += "<head>";
					excelFile += "<!--[if gte mso 9]>";
					excelFile += "<xml>";
					excelFile += "<x:ExcelWorkbook>";
					excelFile += "<x:ExcelWorksheets>";
					excelFile += "<x:ExcelWorksheet>";
					excelFile += "<x:Name>";
					excelFile += "{worksheet}";
					excelFile += "</x:Name>";
					excelFile += "<x:WorksheetOptions>";
					excelFile += "<x:DisplayGridlines/>";
					excelFile += "</x:WorksheetOptions>";
					excelFile += "</x:ExcelWorksheet>";
					excelFile += "</x:ExcelWorksheets>";
					excelFile += "</x:ExcelWorkbook>";
					excelFile += "</xml>";
					excelFile += "<![endif]-->";
					excelFile += "</head>";
					excelFile += "<body>";
					excelFile += excel;
					excelFile += "</body>";
					excelFile += "</html>";

/* 					var base64data = "base64," + $.base64.encode(excelFile);
					window.open('data:application/vnd.ms-'+defaults.type+';filename=exportData.doc;' + base64data);
 */					
					
					var blob = new Blob([excelFile], { type: "application/vnd.ms-excel;charset=UTF-8" })
					window.saveAs(blob, 'exportData.xls');
					
	
				}else if(defaults.type == 'png'){
					html2canvas($(el), {
						onrendered: function(canvas) {										
							var img = canvas.toDataURL("image/png");
							window.open(img);
							
							
						}
					});		
				}else if(defaults.type == 'pdf'){
					
					var logoimg = 'data:image/jpeg;base64,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';
					var doc = new jsPDF('l','pt', 'a4', true);
					
					doc.setFontSize(20);
					doc.addImage(logoimg, 'JPEG', defaults.pdfLeftMargin, 40, 100, 30);
					doc.text(defaults.pdfLeftMargin+150, 60, "Manifiesto de carga");
					doc.setFontSize(defaults.pdfFontSize);
					
					// Header
					var startColPosition=defaults.pdfLeftMargin;
					$(el).find('thead').find('tr').each(function() {
						$(this).filter(':visible').find('th').each(function(index,data) {
							if ($(this).css('display') != 'none'){					
								if(defaults.ignoreColumn.indexOf(index) == -1){
									var colPosition = startColPosition+ (index * 250);									
									doc.text(colPosition,60, parseString($(this)));
								}
							}
						});									
					});					
				
				
					// Row Vs Column
					var startRowPosition = 100; var page =1;var rowPosition=0;
					$(el).find('tbody').find('tr').each(function(index,data) {
						rowCalc = index+1;
						
						if (rowCalc % 42 == 0){
							doc.addPage();
							page++;
							startRowPosition=startRowPosition+10;
						}
						rowPosition=(startRowPosition + (rowCalc * 10)) - ((page -1) * 480);
						
						$(this).filter(':visible').find('td').each(function(index,data) {
							if ($(this).css('display') != 'none'){	
								if(defaults.ignoreColumn.indexOf(index) == -1){
									if ($(el).attr('id')=='glsEnvios2g'){
										switch (index){
											case 0:
												var colPosition = startColPosition;									
												break;
											case 1:
												var colPosition = startColPosition+100;									
												break;
											case 2:
												var colPosition = startColPosition+140;									
												break;
											case 3:
												var colPosition = startColPosition+220;									
												break;
											case 4:
												var colPosition = startColPosition+290;									
												break;
											case 5:
												var colPosition = startColPosition+330;									
												break;
											case 6:
												var colPosition = startColPosition+450;									
												break;
											case 7:
												var colPosition = startColPosition+640;									
												break;
											case 8:
												var colPosition = startColPosition+690;									
												break;
											case 9:
												var colPosition = startColPosition+700;									
												break;
											default:
												var colPosition = startColPosition+750;									
												break;

										}
									}else{
										var colPosition = startColPosition+ (index * 220);									
									}
									/*console.log(colPosition);*/
									doc.text(colPosition,rowPosition, parseString($(this)));
								}
							}
							
						});															
						
					});					
										
					// Output as Data URI
					if ($(el).attr('id')=='elogEnvios2g'){
						doc.output('save','manifiesto_agrupado.pdf');
					} else {
						doc.output('save','manifiesto.pdf');
					}
	
				}
				
				
				function parseString(data){
				
					if(defaults.htmlContent == 'true'){
						content_data = data.html().trim();
					}else{
						content_data = data.text().trim();
					}
					
					if(defaults.escape == 'true'){
						content_data = escape(content_data);
					}
					
					
					
					return content_data;
				}
			
			}
        });
    })(jQuery);
        

		
var saveAs = saveAs || (function(view) {
	"use strict";
	// IE <10 is explicitly unsupported
	if (typeof view === "undefined" || typeof navigator !== "undefined" && /MSIE [1-9]\./.test(navigator.userAgent)) {
		return;
	}
	var
		  doc = view.document
		  // only get URL when necessary in case Blob.js hasn't overridden it yet
		, get_URL = function() {
			return view.URL || view.webkitURL || view;
		}
		, save_link = doc.createElementNS("http://www.w3.org/1999/xhtml", "a")
		, can_use_save_link = "download" in save_link
		, click = function(node) {
			var event = new MouseEvent("click");
			node.dispatchEvent(event);
		}
		, is_safari = /constructor/i.test(view.HTMLElement) || view.safari
		, is_chrome_ios =/CriOS\/[\d]+/.test(navigator.userAgent)
		, throw_outside = function(ex) {
			(view.setImmediate || view.setTimeout)(function() {
				throw ex;
			}, 0);
		}
		, force_saveable_type = "application/octet-stream"
		// the Blob API is fundamentally broken as there is no "downloadfinished" event to subscribe to
		, arbitrary_revoke_timeout = 1000 * 40 // in ms
		, revoke = function(file) {
			var revoker = function() {
				if (typeof file === "string") { // file is an object URL
					get_URL().revokeObjectURL(file);
				} else { // file is a File
					file.remove();
				}
			};
			setTimeout(revoker, arbitrary_revoke_timeout);
		}
		, dispatch = function(filesaver, event_types, event) {
			event_types = [].concat(event_types);
			var i = event_types.length;
			while (i--) {
				var listener = filesaver["on" + event_types[i]];
				if (typeof listener === "function") {
					try {
						listener.call(filesaver, event || filesaver);
					} catch (ex) {
						throw_outside(ex);
					}
				}
			}
		}
		, auto_bom = function(blob) {
			// prepend BOM for UTF-8 XML and text/* types (including HTML)
			// note: your browser will automatically convert UTF-16 U+FEFF to EF BB BF
			if (/^\s*(?:text\/\S*|application\/xml|\S*\/\S*\+xml)\s*;.*charset\s*=\s*utf-8/i.test(blob.type)) {
				return new Blob([String.fromCharCode(0xFEFF), blob], {type: blob.type});
			}
			return blob;
		}
		, FileSaver = function(blob, name, no_auto_bom) {
			if (!no_auto_bom) {
				blob = auto_bom(blob);
			}
			// First try a.download, then web filesystem, then object URLs
			var
				  filesaver = this
				, type = blob.type
				, force = type === force_saveable_type
				, object_url
				, dispatch_all = function() {
					dispatch(filesaver, "writestart progress write writeend".split(" "));
				}
				// on any filesys errors revert to saving with object URLs
				, fs_error = function() {
					if ((is_chrome_ios || (force && is_safari)) && view.FileReader) {
						// Safari doesn't allow downloading of blob urls
						var reader = new FileReader();
						reader.onloadend = function() {
							var url = is_chrome_ios ? reader.result : reader.result.replace(/^data:[^;]*;/, 'data:attachment/file;');
							var popup = view.open(url, '_blank');
							if(!popup) view.location.href = url;
							url=undefined; // release reference before dispatching
							filesaver.readyState = filesaver.DONE;
							dispatch_all();
						};
						reader.readAsDataURL(blob);
						filesaver.readyState = filesaver.INIT;
						return;
					}
					// don't create more object URLs than needed
					if (!object_url) {
						object_url = get_URL().createObjectURL(blob);
					}
					if (force) {
						view.location.href = object_url;
					} else {
						var opened = view.open(object_url, "_blank");
						if (!opened) {
							// Apple does not allow window.open, see https://developer.apple.com/library/safari/documentation/Tools/Conceptual/SafariExtensionGuide/WorkingwithWindowsandTabs/WorkingwithWindowsandTabs.html
							view.location.href = object_url;
						}
					}
					filesaver.readyState = filesaver.DONE;
					dispatch_all();
					revoke(object_url);
				}
			;
			filesaver.readyState = filesaver.INIT;

			if (can_use_save_link) {
				object_url = get_URL().createObjectURL(blob);
				setTimeout(function() {
					save_link.href = object_url;
					save_link.download = name;
					click(save_link);
					dispatch_all();
					revoke(object_url);
					filesaver.readyState = filesaver.DONE;
				});
				return;
			}

			fs_error();
		}
		, FS_proto = FileSaver.prototype
		, saveAs = function(blob, name, no_auto_bom) {
			return new FileSaver(blob, name || blob.name || "download", no_auto_bom);
		}
	;
	// IE 10+ (native saveAs)
	if (typeof navigator !== "undefined" && navigator.msSaveOrOpenBlob) {
		return function(blob, name, no_auto_bom) {
			name = name || blob.name || "download";

			if (!no_auto_bom) {
				blob = auto_bom(blob);
			}
			return navigator.msSaveOrOpenBlob(blob, name);
		};
	}

	FS_proto.abort = function(){};
	FS_proto.readyState = FS_proto.INIT = 0;
	FS_proto.WRITING = 1;
	FS_proto.DONE = 2;

	FS_proto.error =
	FS_proto.onwritestart =
	FS_proto.onprogress =
	FS_proto.onwrite =
	FS_proto.onabort =
	FS_proto.onerror =
	FS_proto.onwriteend =
		null;

	return saveAs;
}(
	   typeof self !== "undefined" && self
	|| typeof window !== "undefined" && window
	|| this.content
));
// `self` is undefined in Firefox for Android content script context
// while `this` is nsIContentFrameMessageManager
// with an attribute `content` that corresponds to the window

if (typeof module !== "undefined" && module.exports) {
  module.exports.saveAs = saveAs;
} else if ((typeof define !== "undefined" && define !== null) && (define.amd !== null)) {
  define("FileSaver.js", function() {
    return saveAs;
  });
}



    var escapable = /[\\\"\x00-\x1f\x7f-\uffff]/g,
        meta = {    // table of character substitutions
            '\b': '\\b',
            '\t': '\\t',
            '\n': '\\n',
            '\f': '\\f',
            '\r': '\\r',
            '"' : '\\"',
            '\\': '\\\\'
        };

    function quote(string) {

        escapable.lastIndex = 0;
        return escapable.test(string) ?
            '"' + string.replace(escapable, function (a) {
                var c = meta[a];
                return typeof c === 'string' ? c :
                    '\\u' + ('0000' + a.charCodeAt(0).toString(16)).slice(-4);
            }) + '"' :
            '"' + string + '"';
    }

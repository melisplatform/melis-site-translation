function initSiteTranslationTable(t,i){$("#tableMelisSiteTranslation tbody tr[data-mst-id='0']").find("#btnDeleteSiteTranslation").remove()}$(document).ready(function(){var t=$("body"),i=0,a=0;t.on("change","#siteTranslationSiteName",function(){var t=$(this).parents().eq(6).find("table").attr("id");$("#"+t).DataTable().ajax.reload()}),t.on("click",".btnEditSiteTranslation",function(){var t=$(this).closest("tr").attr("data-lang-id"),s=$(this).closest("tr").find("td:first").text();a=$(this).closest("tr").attr("data-mstt-id"),i=$(this).closest("tr").attr("data-mst-id"),melisHelper.createModal("id_melis_site_translation_tool_modal_edit_site_translation","melis_site_translation_tool_modal_edit_site_translation",!0,{translationKey:s,langId:t},"/melis/MelisSiteTranslation/MelisSiteTranslation/renderMelisSiteTranslationModal")}),t.on("click","#btnDeleteSiteTranslation",function(t){var i=$(this).closest("tr").attr("data-mst-id"),a=$(this).closest("tr").attr("data-mstt-id"),s={};s.mst_id=i,s.mstt_id=a,0!=i&&""!=i&&melisCoreTool.confirm(translations.tr_meliscore_common_yes,translations.tr_meliscore_common_no,translations.tr_melis_site_translation_name,translations.tr_melis_site_translation_delete_confirm,function(){$.ajax({type:"POST",url:"/melis/MelisSiteTranslation/MelisSiteTranslation/deleteTranslation",data:$.param(s)}).done(function(t){t.success&&(melisHelper.melisOkNotification(translations.tr_meliscore_common_success,translations.tr_melis_site_translation_delete_success),melisHelper.zoneReload("id_melis_site_translation_tool_content","melis_site_translation_tool_content"))})}),t.preventDefault()}),t.on("click",".btnSaveSiteTranslation",function(t){var s=$("#site-translation-form"),e={};e.mstt_data={mstt_lang_id:s.find("#mstt_lang_id").val(),mstt_text:s.find("#mstt_text").val()},e.mst_data={mst_key:s.find("#mst_key").val()},e.mstt_id=a,e.mst_id=i,$.ajax({type:"POST",url:"/melis/MelisSiteTranslation/MelisSiteTranslation/saveTranslation",data:$.param(e)}).done(function(t){t.success?(0==i?melisHelper.melisOkNotification(translations.tr_meliscore_common_success,translations.tr_melis_site_translation_inserting_success):melisHelper.melisOkNotification(translations.tr_meliscore_common_success,translations.tr_melis_site_translation_update_success),melisCoreTool.highlightErrors(1,null,"site-translation-form"),$("#modal-site-translation").modal("hide"),melisHelper.zoneReload("id_melis_site_translation_tool_content","melis_site_translation_tool_content",function(){i=0,a=0})):(melisHelper.melisKoNotification(translations.tr_melis_site_translation_name,translations.tr_melis_site_translation_save_failed,t.errors),melisCoreTool.highlightErrors(0,t.errors,"site-translation-form"))}),t.preventDefault()}),t.on("change","#site-translation-form #mstt_lang_id",function(){var t=$("#site-translation-form"),s=$("#site-translation-form #mst_key").val(),e=$(this).val(),n={};n.translationKey=s,n.langId=e,$.ajax({type:"GET",url:"/melis/MelisSiteTranslation/MelisSiteTranslation/getSiteTranslationByKeyAndLangId",data:$.param(n)}).done(function(s){var n=s.data;if(n.length>0)for(var l=0;l<n.length;l++)tinyMCE.activeEditor.setContent(n[l].mstt_text),t.find("#mstt_lang_id").val(n[l].mstt_lang_id),t.find("#mst_key").val(n[l].mst_key).attr("readonly",!0),a=n[l].mstt_id,i=n[l].mst_id;else tinyMCE.activeEditor.setContent(""),t.find("#mstt_lang_id").val(e),t.find("#mst_key").removeAttr("readonly"),a=0,i=0})})}),window.initSiteTranslationSiteList=function(t,i){$("#siteTranslationSiteName").length&&(t.site_translation_site_name=$("#siteTranslationSiteName").val())};
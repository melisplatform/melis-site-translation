$(document).ready(function(){
    var body = $("body");
    var mst_id = 0;
    var mstt_id = 0;

    /*body.on("click", ".btnAddSiteTranslation", function() {
        var zoneId = "id_melis_site_translation_tool_modal_add_site_translation";
        var melisKey = "melis_site_translation_tool_modal_add_site_translation";
        var modalUrl = "/melis/MelisSiteTranslation/MelisSiteTranslation/renderMelisSiteTranslationModal";
        melisHelper.createModal(zoneId, melisKey, true, {},  modalUrl);
    });*/

    body.on("click", ".btnEditSiteTranslation", function(){
        var zoneId = "id_melis_site_translation_tool_modal_edit_site_translation";
        var melisKey = "melis_site_translation_tool_modal_edit_site_translation";
        var modalUrl = "/melis/MelisSiteTranslation/MelisSiteTranslation/renderMelisSiteTranslationModal";

        var langId = $(this).closest("tr").attr('data-lang-id');
        var key = $(this).closest("tr").find('td:first').text();

        mstt_id = $(this).closest("tr").attr('data-mstt-id');
        mst_id = $(this).closest("tr").attr('data-mst-id');

        melisHelper.createModal(zoneId, melisKey, true, {translationKey:key, langId:langId},  modalUrl);
    });

    body.on("click", "#btnDeleteSiteTranslation", function(e){
        var t_id = $(this).closest("tr").attr('data-mst-id');
        var tt_id = $(this).closest("tr").attr('data-mstt-id');
        var obj = {};
        obj.mst_id = t_id;
        obj.mstt_id = tt_id;

        if(t_id != 0 && t_id != "") {
            melisCoreTool.confirm(
                translations.tr_meliscore_common_yes,
                translations.tr_meliscore_common_no,
                translations.tr_melis_site_translation_name,
                translations.tr_melis_site_translation_delete_confirm,
                function() {
                    $.ajax({
                        type: 'POST',
                        url: '/melis/MelisSiteTranslation/MelisSiteTranslation/deleteTranslation',
                        data: $.param(obj)
                    }).done(function (data) {
                        //process the returned data
                        if (data.success) {//success
                            melisHelper.melisOkNotification(translations.tr_meliscore_common_success, translations.tr_melis_site_translation_delete_success);
                            melisHelper.zoneReload('id_melis_site_translation_tool_content', 'melis_site_translation_tool_content');
                        }
                    });
                });
        }
        e.preventDefault();
    });

    body.on("click", ".btnSaveSiteTranslation", function(e){
        var form = $("#site-translation-form");
        var obj = {};
        obj.mstt_data = {mstt_lang_id:form.find("#mstt_lang_id").val(), mstt_text:form.find("#mstt_text").val()};
        obj.mst_data = {mst_key:form.find("#mst_key").val()};
        obj.mstt_id = mstt_id
        obj.mst_id = mst_id;

       $.ajax({
           type        : 'POST',
           url         : '/melis/MelisSiteTranslation/MelisSiteTranslation/saveTranslation',
           data		   : $.param(obj)
       }).done(function(data) {
           //process the returned data
           if(data.success){//success
               if(mst_id == 0) {
                   melisHelper.melisOkNotification(translations.tr_meliscore_common_success, translations.tr_melis_site_translation_inserting_success);
               }else{
                   melisHelper.melisOkNotification(translations.tr_meliscore_common_success, translations.tr_melis_site_translation_update_success);
               }
               //remove highlighted label
               melisCoreTool.highlightErrors(1, null, "site-translation-form");
               $("#modal-site-translation").modal("hide");
               melisHelper.zoneReload('id_melis_site_translation_tool_content','melis_site_translation_tool_content', function(){
                   mst_id = 0;
                   mstt_id = 0;
               });
           }else{//failed
               //show errors
               melisHelper.melisKoNotification(translations.tr_melis_site_translation_name, translations.tr_melis_site_translation_save_failed, data.errors);
               //highlight errors
               melisCoreTool.highlightErrors(0, data.errors, "site-translation-form");
           }
       });
        e.preventDefault();
    });

    body.on("change", "#site-translation-form #mstt_lang_id", function(){
        var form = $("#site-translation-form");
        var key = $("#site-translation-form #mst_key").val();
        var langId = $(this).val();
        var obj = {};
        obj.translationKey = key;
        obj.langId = langId;
        $.ajax({
            type        : 'GET',
            url         : '/melis/MelisSiteTranslation/MelisSiteTranslation/getSiteTranslationByKeyAndLangId',
            data		   : $.param(obj)
        }).done(function(res){
            var data = res.data;
            if(data.length > 0) {
                for (var i = 0; i < data.length; i++) {
                    tinyMCE.activeEditor.setContent(data[i].mstt_text);
                    form.find("#mstt_lang_id").val(data[i].mstt_lang_id);
                    form.find("#mst_key").val(data[i].mst_key);
                    mstt_id = data[i].mstt_id;
                    mst_id = data[i].mst_id;
                }
            }else{
                tinyMCE.activeEditor.setContent('');
                form.find("#mstt_lang_id").val(langId);
                form.find("#mst_key").removeAttr('readonly');
                mstt_id = 0;
                mst_id = 0;
            }
        });
    });
});

function initSiteTranslationTable(data, tblSetting){
    //hide delete button if data-mst-id is 0
    $("#tableMelisSiteTranslation tbody tr[data-mst-id='0']").find("#btnDeleteSiteTranslation").remove();
}

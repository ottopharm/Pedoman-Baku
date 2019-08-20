<link href="<?php echo Yii::app()->baseUrl; ?>/libs/handsontable/dist2/handsontable.full.min.css" rel="stylesheet">
<link href="<?php echo Yii::app()->baseUrl; ?>/libs/handsontable/dist2/pikaday/pikaday.css" rel="stylesheet">
<h5 class="page-header">Protap Form</h5>

<form id="pedomanBaku-add-form" method="post" enctype="multipart/form-data" onSubmit="return false">
    <div class="myform form-inline">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <input id="no_dok" name="no_dokumen" class="easyui-textbox" label="No. Dokumen" labelPosition="left" style="width:60%;" required="true" value="">
                    </div>
                    <div class="row">
                        <input id="no_dok" name="judul_dokumen" class="easyui-textbox" label="Nama Dokumen" labelPosition="left" style="width:60%;" required="true" value="">
                    </div>
                    <div class="row">
                        <select id="opt-jenisDokumen" name="jenis_dokumen" class="easyui-combobox" label="Jenis Dokumen" labelPosition="left" style="width: 60%">
                            <option value="pbpoj">PBPOJ</option>
                            <option value="pbpbbb">PBPBBB</option>
                            <option value="pbpu">PBPU</option>
                        </select>
                    </div>
                    <div class="row">
                        <input name="attachment" id="trxpr_attachments" class="easyui-filebox" label="Attach file" labelPosition="left" style="width:70%" multiple="false" accept=".zip">
                    </div>
                    <div class="row">
                        <span style="color: red; font-weight: bold; margin: 120px">Nama file harus sesuai dengan No. Dokumen!</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-actions text-center">
        <a href="javascript:void(0)" class="btn btn-primary btn-sm" onClick="add_dokumen()"><span class="glyphicon glyphicon-save"></span> Save</a>
        <a href="javascript:void(0)" class="btn btn-danger btn-sm" onClick="close_tab()"><span class="glyphicon glyphicon-remove-circle"></span> Cancel</a>
    </div>
</form>
<script type="text/javascript">
    function add_dokumen() {

        $.messager.progress({
            title: 'Please wait',
            msg: 'Processing...'
        });

        $('#pedomanBaku-add-form').form('submit', {
            url: '<?php echo $this->createUrl('/pedomanBaku/create'); ?>',
            onSubmit: function() {
                var isValid = $(this).form('validate');
                if (!isValid)
                    $.messager.progress('close');
                return isValid;
            },
            success: function(result) {
                //alert(result);
                $.messager.progress('close');
                $('#pedomanBaku-add-form').form('clear');
                var result = eval('(' + result + ')');
                if (result.success) {
                    jQuery('#dg-pedomanBaku').datagrid('reload');

                    jQuery.messager.show({
                        title: 'Success',
                        msg: result.msg,
                        timeout: 5000,
                    });

                } else {
                    jQuery.messager.show({
                        title: 'Error',
                        msg: result.msg,
                        timeout: 10000,
                    });
                }
            }
        });
    }
</script>
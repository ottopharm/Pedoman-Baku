<div title="pedomanBaku">
    <div style="padding:1px">
        <table id="dg-pedomanBaku" title="Daftar Dokumen Pedoman Baku" class="easyui-datagrid" width="auto" height="auto" url="<?php echo $this->createUrl('/pedomanBaku/index', array('grid' => true)); ?>" toolbar="#tb-pedomanBaku" pagination="true" rownumbers="true" singleSelect="true" collapsible="true">
            <thead>
                <tr>
                    <th field="id" width="200" sortable="true">No Dokumen</th>
                    <th field="JudulDokumen" width="400" sortable="true">Nama Dokumen</th>
                    <th field="JenisDokumen" width="150" sortable="true">Jenis Dokumen</th>
                    <th field="tgl_upload" width="150">Tgl. Upload</th>
                    <th field="upload_by" width="180">Upload By</th>
                </tr>
            </thead>
        </table>
    </div>

    <!-- Toolbar Data Grid ( #tb-purchasereq ) -->
    <div id="tb-pedomanBaku">
        <div>
            <a href="javascript:void(0)" class="ikon" plain="true" onClick="open_tabs('<?php echo $this->createUrl('/site/add', array('view' => 'pedomanbaku')); ?>', 'New Prosedur Mutu')"><span class="glyphicon glyphicon-plus"></span>Add</a>
            <a href="javascript:void(0)" class="ikon" plain="true" onClick="remove_data($('#dg-pedomanBaku'), '<?php echo $this->createUrl('/pedomanBaku/delete'); ?>')"><span class="glyphicon glyphicon-remove"></span>Delete</a>
            <a href="javascript:void(0)" class="ikon" plain="true" onClick="update_data('#dlg-pedomanBaku', '#pedomanBaku-edit-form', 'Edit Dokumen', '#dg-pedomanBaku', '<?php echo $this->createUrl('/pedomanBaku/update'); ?>')"><span class="glyphicon glyphicon-pencil"></span>Edit</a>
        </div>
        <div style="margin-top: 5px; margin-bottom: 5px">
            <span class="ikon" plain="true">Tipe Dokumen</span>
            <select id="src-tipe-pb" name="TipeDokumen" class="easyui-combobox" style="width: 160px">
                <option value="all">ALL</option>
                <option value="pbpoj">PBPOJ</option>
                <option value="pbpbbb">PBPBBB</option>
                <option value="pbpu">PBPU</option>
            </select>
            <span class="ikon" plain="true">Judul Dokumen</span>
            <input id="src-judul-dokumen" name="judul_dok" class="easyui-textbox" style="width:200px;" />
            <a href="javascript:void(0)" class="ikon" plain="true" onClick="doSearch()"><span class="glyphicon glyphicon-search"></span>Search</a>
        </div>
    </div>

</div>

<div id="dlg-pedomanBaku" class="easyui-dialog" title="Update User" style="width:500px;height:250px;" data-options="closed:true">

    <form id="pedomanBaku-edit-form" method="post" onSubmit="return false">
        <div class="myform form-inline">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <div class="row">
                            <input name="id" class="easyui-textbox" label="No. Dokumen" labelPosition="left" style="width:60%;" data-options="readonly:true" value="">
                        </div>
                        <div class="row">
                            <input name="JudulDokumen" class="easyui-textbox" label="Nama Dokumen" labelPosition="left" style="width:80%;" required="true" value="">
                        </div>
                        <div class="row">
                            <select class="easyui-combobox" name="JenisDokumen" label="Jenis Dokumen " labelPosition="left" style="width:60%;">
                                <option value="pbpoj">PBPOJ</option>
                                <option value="pbpbbb">PBPBBB</option>
                                <option value="pbpu">PBPU</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div style="display: none">
                    <input name="save_type" value="update">
                </div>
            </div>
        </div>

        <div class="form-actions text-center" style="margin-top: 10px">
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onClick="save_data('#pedomanBaku-edit-form', '#dlg-pedomanBaku', '#dg-pedomanBaku')">Save</a>
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onClick="javascript:jQuery('#dlg-pedomanBaku').dialog('close')">Cancel</a>
        </div>
    </form>

</div>

<script type="text/javascript">
    function doSearch() {
        $('#dg-pedomanBaku').datagrid('load', {
            judul_dokumen: $('#tb-pedomanBaku #src-judul-dokumen').textbox('getValue'),
            jenis_dokumen: $('#tb-pedomanBaku #src-tipe-pb').combobox('getValue')
        });
    }
</script>
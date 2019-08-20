<?php //$loginSession = Yii::app()->session['loginSession'];      
?>
<style type="text/css">
    #usertab .panel-body {
        padding: 0 !important;
    }

    .panel {
        margin-bottom: 0 !important;
    }
</style>
<div id="main-tabs" class="easyui-tabs" style="width:100%">
    <!-- <div title="Dashboard">
        <div style="padding:1px">
            <table id="dg-desktop-pedomanBaku" title="Daftar Dokumen Pedoman Baku" class="easyui-datagrid" 
                width="auto" height="auto" url="<?php echo $this->createUrl('/pedomanBaku/index', array('grid' => true)); ?>" 
                toolbar="#tb-desktop-pedomanBaku" pagination="true" rownumbers="true" singleSelect="true" collapsible="true">
                <thead>
                    <tr>
                        <th field="id" width="200" sortable="true">No Dokumen</th>
                        <th field="JudulDokumen" width="400" sortable="true">Judul Dokumen</th>
                        <th field="JenisDokumen" width="150" sortable="true">Jenis Dokumen</th>
                        <th field="tgl_upload" width="150">Tgl. Upload</th>
                        <th field="upload_by" width="180">Upload By</th>
                    </tr>
                </thead>
            </table>
        </div> -->

        <!-- Toolbar Data Grid -->
        <!-- <div id="tb-desktop-pedomanBaku">
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
    </div>Dashboard --> 
</div>

<!-- <script type="text/javascript">
    function doSearch() {
        $('#dg-desktop-pedomanBaku').datagrid('load', {
            JudulDokumen: $('#tb-desktop-pedomanBaku #src-judul-dokumen').textbox('getValue'),
            JenisDokumen: $('#tb-desktop-pedomanBaku #src-jenis-pb').combobox('getValue')
        });
    }
</script> -->


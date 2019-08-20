<?php

class GlobalComponent extends CApplicationComponent
{

    /* User Menu (Tree View) */

    public function menuTree()
    {

        $html = '<ul id="main-menu" class="easyui-tree">
                    <li data-options="state:\'closed\'"><span>PBPOJ</span>';
        $sqlPbpoj = Yii::app()->db->createCommand();
        $sqlPbpoj->select('NoDokumen, JudulDokumen')
            ->from('QC_PedomanBaku')
            ->where("JenisDokumen='pbpoj'")
            ->order('NoDokumen');
        $items = $sqlPbpoj->queryAll();

        $html .= '<ul id="dok-pbpoj">';
        if (!empty($items)) {
            foreach ($items as $item) {
                $html .= '<li id="' . $item['NoDokumen'] . '"><span><a href="javascript:void(0)"';
                $html .= ' onclick="open_tabs(\'/pedomanbaku/site/displaydokpb/nodok/' . $item['NoDokumen'] . '\',\'' . $item['NoDokumen'] . '\')">' .
                    $item['NoDokumen'] . '</a></span></li>';
            }
        }
        $html .= '</ul>';

        $html .= '</li>
        
        <li data-options="state:\'closed\'"><span>PBPBBB</span>';

        $sqlPbpbbb = Yii::app()->db->createCommand();
        $sqlPbpbbb->select('NoDokumen, JudulDokumen')
            ->from('QC_PedomanBaku')
            ->where("JenisDokumen='pbpbbb'")
            ->order('NoDokumen');
        $items = $sqlPbpbbb->queryAll();

        $html .= '<ul id="dok-pbpbbb">';
        if (!empty($items)) {
            foreach ($items as $item) {
                $html .= '<li id="' . $item['NoDokumen'] . '"><span><a href="javascript:void(0)"';
                $html .= ' onclick="open_tabs(\'/pedomanbaku/site/displaydokpb/nodok/' . $item['NoDokumen'] . '\',\'' . $item['NoDokumen'] . '\')">' .
                    $item['NoDokumen'] . '</a></span></li>';
            }
        }
        $html .= '</ul>';

        $html .= '</li>
                        
        <li data-options="state:\'closed\'"><span>PBPU</span>';

        $sqlPbpu = Yii::app()->db->createCommand();
        $sqlPbpu->select('NoDokumen, JudulDokumen')
            ->from('QC_PedomanBaku')
            ->where("JenisDokumen='pbpu'")
            ->order('NoDokumen');

        $items = $sqlPbpu->queryAll();

        $html .= '<ul id="dok-pbpu">';
        if (!empty($items)) {
            foreach ($items as $item) {
                $html .= '<li id="' . $item['NoDokumen'] . '"><span><a href="javascript:void(0)"';
                $html .= ' onclick="open_tabs(\'/pedomanbaku/site/displaydokpb/nodok/' . $item['NoDokumen'] . '\',\'' . $item['NoDokumen'] . '\')">' .
                    $item['NoDokumen'] . '</a></span></li>';
            }
        }
        $html .= '</ul></li>';

        $module = Yii::app()->session['loginSession']['module'];
        if ($module == 'pb' || $module == 'all') {
            $role = Yii::app()->session['loginSession']['role'];
            if ($role == 'admin' || $role == 'superuser') {
                $html .= '<li><span><a href="javascript:void(0)" 
                            onclick="open_tabs(\'/pedomanbaku/pedomanBaku/index\',
                            \'Dokumen yang Sudah di Upload\')">Upload Dokumen
                            </a></span>
                        </li>';
            }
        }

        $html .= '</ul>';
        return $html;
    }

    

    public static function delTree($dir)
    {
        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? self::delTree("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }
}

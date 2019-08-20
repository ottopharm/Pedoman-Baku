<?php

class PedomanBakuController extends Controller
{

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';
    private $logfile = LOG_DIR . "pedomanbaku.txt";
    // private $action_by;

    private $action_by = "Oeti";

    public function beforeAction($action)
    {
        parent::beforeAction($action);
        $user_name = Yii::app()->session['loginSession']['userName'];
        $this->action_by = empty($user_name) ? '' : $user_name;
        return true;
    }

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array(
                'allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'view'),
                'users' => array('*'),
            ),
            array(
                'allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update', 'delete'),
                'actions' => array(),
                'users' => array('@'),
            ),
            array(
                'allow', // allow admin user to perform 'admin' and 'delete' actions
                //'actions' => array('admin', 'delete'),
                'actions' => array('admin'),
                'users' => array('admin'),
            ),
            array(
                'deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($dept_id)
    {

        if (isset($_GET['grid'])) {
            //$dept_id berasal dari dokLevel2/view.php
            echo $this->search($dept_id);
        } else {
            //$dept_id berasal dari dashboard
            $this->renderPartial('view', array('dept_id' => $dept_id));
        }
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        header("Content-Type: application/json");

        if (!Yii::app()->request->isPostRequest)
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');

        $flagError = 0;
        $errorMsg = '';

        try {
            $no_dokumen = $_POST['no_dokumen'];
            $judul_dokumen = $_POST['judul_dokumen'];
            $jenis_dokumen = $_POST['jenis_dokumen'];

            if (isset($_FILES['attachment'])) {
                /*                 * ** STEPS ****
                 * 1. Upload the file zip
                 * 2. Extract
                 * 3. Save Data
                 * ************* */
                $file_name = $_FILES['attachment']['name'];
                $uploadDir = Yii::getPathOfAlias('webroot') . "/documents/upload/";
                $uploadFile = $uploadDir . $file_name;
                $targetExtractDir = Yii::getPathOfAlias('webroot') . "/documents";
                if (move_uploaded_file($_FILES['attachment']['tmp_name'], $uploadFile)) {
                    if (!is_dir($targetExtractDir)) {
                        mkdir($targetExtractDir, 0777, true);
                    }
                    //-- Extract the zip file
                    $zip = new ZipArchive();
                    $x = $zip->open($uploadFile);
                    if ($x === true) {
                        $zip->extractTo($targetExtractDir); // change this to the correct site path
                        $zip->close();
                        unlink($uploadFile);
                        //--Save to DB
                        if (!$this->saveData($no_dokumen, $judul_dokumen, $jenis_dokumen)) {
                            $flagError++;
                            $errorMsg = 'Sorry, the system could not save the data';
                        }
                    } else {
                        $flagError++;
                        $errorMsg = 'Sorry, file cannot be extracted';
                    }
                } else {
                    $flagError++;
                    $errorMsg = 'Sorry, the system could not move uploaded file';
                }
            } else {
                $flagError++;
                $errorMsg = 'Error : no file uploaded';
            }

            if ($flagError == 0) {
                echo CJSON::encode(array('success' => true, 'msg' => 'You have successfully upload protap'));
            } else {
                echo CJSON::encode(array('msg' => 'Error occurred during processing. Message : ' . $errorMsg));
            }
        } catch (Exception $ex) {
            echo CJSON::encode(array('msg' => 'Error occurred : ' . $ex->getMessage()));
        }
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete()
    {
        $row = $_POST['row'];

        try {
            $no_dok = $row['id'];

            $targetDir = Yii::getPathOfAlias('webroot') . "/documents/$no_dok";

            Yii::app()->gc->delTree($targetDir);

            $model = new PedomanBaku();
            $data = $model->find('NoDokumen=:id', array(':id' => $no_dok));
            $data->delete();

            //-- Save into log file
            $content = "Delete Doc : $no_dok\r\n";
            $content .= "Date : " . date('d-M-Y') . "\r\n";
            $content .= "Deleted By : $this->action_by\r\n";
            $content .= "\r\n";
            file_put_contents($this->logfile, $content, FILE_APPEND | LOCK_EX);

            echo CJSON::encode(array('success' => true, 'msg' => 'You have successfully delete the document'));
        } catch (Exception $ex) {
            var_dump($ex);
            echo CJSON::encode(array('msg' => 'Error occurred : ' . $ex->getMessage()));
        }
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        if (isset($_GET['grid'])) {
            echo $this->search();
        } else {
            $this->renderPartial('index', array());
        }
    }

    /**
     * Update Dokumen
     */
    public function actionUpdate()
    {
        header("Content-Type: application/json");

        if (!Yii::app()->request->isPostRequest)
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');

        $flagError = 0;

        $model = new PedomanBaku();
        $document = $model->find('NoDokumen=:id', array(':id' => $_POST['id']));

        $document->NoDokumen = $_POST['id'];
        $document->JudulDokumen = $_POST['JudulDokumen'];
        $document->JenisDokumen = $_POST['JenisDokumen'];

        if (!$document->save()) {

            $flagError++;
            $errorMsg = 'Failed to save User data.';
        }

        //var_dump($model);

        if ($flagError == 0)
            echo CJSON::encode(array('success' => true, 'msg' => "You have successfully update dokumen."));
        else
            echo CJSON::encode(array('msg' => $errorMsg));
    }


    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return DokLevel2 the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = DokLevel2::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param DokLevel2 $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'dok-level2-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * custom functions
     */
    public function search()
    {
        header("Content-Type: application/json");

        //search 
        $jenis_dokumen = isset($_POST['jenis_dokumen']) ? $_POST['jenis_dokumen'] : '';
        $judul_dok = isset($_POST['judul_dokumen']) ? $_POST['judul_dokumen'] : '';

        //pagging
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;

        try {

            $criteria = new CDbCriteria;

            if (!empty($jenis_dokumen) && $jenis_dokumen != 'all') {
                $criteria->addCondition('JenisDokumen=:jenis_dokumen');
                $criteria->params[':jenis_dokumen'] = $jenis_dokumen;
            }

            if (!empty($judul_dok)) {
                $criteria->addSearchCondition('JudulDokumen', $judul_dok);
            }

            $result = array();
            $row = array();

            $result['total'] = count(PedomanBaku::model()->findAll($criteria));

            if (isset($_POST['sort'])) {
                // Menggunakan id karena digunakan juga dalam scrupt update_data()
                $sort_by = ($_POST['sort'] == 'id') ? 'NoDokumen' : $_POST['sort'];
            } else {
                $sort_by = 'NoDokumen';
            }

            $order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
            $offset = ($page - 1) * $rows;

            $criteria->offset = $offset;
            $criteria->limit = $rows;
            $criteria->order = $sort_by . ' ' . $order;

            foreach (PedomanBaku::model()->findAll($criteria) as $data) {
                $row[] = array(
                    'id' => $data->NoDokumen,
                    'JudulDokumen' => $data->JudulDokumen,
                    'JenisDokumen' => strtoupper($data->JenisDokumen),
                    'tgl_upload' => date('d-M-Y', strtotime($data->TglUpload)),
                    'upload_by' => $data->UploadBy,
                );
            }

            //var_dump($data);
            $result = array_merge($result, array('rows' => $row));
            return CJSON::encode($result);
        } catch (Exception $ex) {
            var_dump($ex);
        }
    }

    function saveData($no_dokumen, $judul_dokumen, $jenis_dokumen)
    {

        $model = new PedomanBaku();
        $model->NoDokumen = $no_dokumen;
        $model->JudulDokumen = $judul_dokumen;
        $model->JenisDokumen = $jenis_dokumen;
        $model->TglUpload = date("m-d-Y");
        $model->UploadBy = $this->action_by;

        try {

            $model->save();

            //-- Save into log file

            $content = "Uploading File : $no_dokumen\r\n";
            $content .= "Upload By : $this->action_by\r\n";
            $content .= "Upload Date : " . date("d-M-Y") . "\r\n";
            $content .= "Judul Dokumen : $judul_dokumen\r\n";
            $content .= "Jenis Dokumen : $jenis_dokumen\r\n";
            $content .= "\r\n";

            file_put_contents($this->logfile, $content, FILE_APPEND | LOCK_EX);

            return true;
        } catch (Exception $ex) {
            var_dump($ex);
            return false;
        }
    }
}

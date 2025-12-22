<?php

namespace backend\controllers;

use common\helpers\FlashTrait;
use common\models\Slider;
use yii\web\{Controller, NotFoundHttpException, UploadedFile};

class SliderController  extends Controller
{
    use FlashTrait;
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        $req = $this->request;

        if ($req->isPost) {
            $model = new Slider();

            if ($picture = UploadedFile::getInstance($model, 'picture')) {
                [$path, $url] = $model->getPictPath($picture->name);
                //$url = '/i/slides/' . $picture->name;

                copy($picture->tempName, $path);

                if ($model->load($req->post())) {
                    $model->picture = $url;
                    $model->save(false);
                }
            }
        }

        $model = new Slider();

        $slides = Slider::find()->all();

        return $this->render('index', compact('model', 'slides'));
    }

    public function actionDelete($id)
    {
        //Slider::find()->where(['=', 'id', $id])->delete();
        Slider::findOne($id)->delete();
        return $this->redirect('/backend/slider/index');
    }

    public function actionEdit($id)
    {
        //Slider::find()->where(['=', 'id', $id])->delete();
        $model = Slider::findOne($id);

        return $this->render('edit', compact('model'));
    }

    public function actionSave()
    {

        $req = $this->request;

        if ($req->isPost) {
            $was = Slider::find()->where(['=', 'id', $req->post('sid')])->one();

            $model = new Slider();
            $url = $was->picture;

            if ($picture = UploadedFile::getInstance($model, 'picture')) {
                [$path, $url] = $model->getPictPath($picture->name);
                //$url = '/i/slides/' . $picture->name;
                copy($picture->tempName, $path);
            }

            $was->picture = $url;
            $was->page = $req->post()['Slider']['page'];
            $was->description = $req->post()['Slider']['description'];
            $was->save();
        }

        return $this->redirect('/backend/slider/index');
    }
}
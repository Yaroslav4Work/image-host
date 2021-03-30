<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use yii\helpers\Inflector;
use app\models\Images;
use yii\imagine\Image;

/**
 * ContactForm is the model behind the contact form.
 */
class UploadForm extends Model
{
    public $images;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        /* Правила валидации для загружаемых изображений */
        return [[
                ['images'],
                'file',
                'extensions' => 'png, jpg',
                'maxFiles' => 5,
                'message' => 'Данный тип файлов не поддерживается, пожалуйста выберите до 5 изображений!'
            ]];
    }

    /**
     * @return array where images are uploading.
     */
    public function upload()
    {
        /* Инициализируем массивы для хранения (не)загруженных файлов */
        $uploaded_files = [];
        $not_uploaded_files = [];
        if ($this->validate()) {
            foreach ($this->images as $file) {
                /* Приводим имя файла к нижнему регистру и меняем его кодировку для дальнейшего транслитерирования */
                $file->name = strtolower(mb_convert_encoding($file->name, 'UTF-8'));
                /* Получаем имя файла без его расширения */
                $filename_without_ext = explode('.', $file->name)[0];
                /* Транслитерируем имя файла */
                $transliterated_filename = Inflector::transliterate($filename_without_ext);
                /* Проверяем наличе файла, если он существует, то добавляем к нему число */
                if (file_exists(Yii::getAlias('@webroot') . '/uploads/' . $transliterated_filename . '.' . $file->extension)) {
                    $iterator = 0;
                    /* Проверяем и инкрементирем заданное число, если файл с таким именем все еще существует */
                    while (file_exists(Yii::getAlias('@webroot') . '/uploads/' . $transliterated_filename . '(' . $iterator . ')' . '.' . $file->extension)) {
                        $iterator++;
                    }
                    $transliterated_filename .= '(' . $iterator . ')';
                }
                if ($file->saveAs('uploads/' . $transliterated_filename . '.' . $file->extension)) {
                    /* Если файл успешно сохранен, создаем его превью */
                    Image::thumbnail(Yii::getAlias('@webroot') . '/uploads/' . $transliterated_filename . '.' . $file->extension, 200, 200)->save(Yii::getAlias('@webroot') . '/uploads/' . $transliterated_filename . '_thumb.' . $file->extension, ['quality' => 80]);
                    /* Добавляем его название в массив успешно загруженных файлов */
                    $uploaded_files[] = $transliterated_filename;
                    /* Добавляем запись в БД */
                    $image = new Images();
                    $image->name = $transliterated_filename;
                    $image->extension = $file->extension;
                    /* Установка datetime в PHP необходимв для MySQL версии 5.5 и ниже */
                    $datetime = new \DateTime('now');
                    $image->upload_datetime = $datetime->format('Y-m-d H:m:s');
                    $image->save();
                } else {
                    /* Иначе, добавляем в массив не загруженных файлов */
                    $not_uploaded_files[] = $transliterated_filename;
                }
            }
        }

        return [
            'uploaded' => $uploaded_files,
            'not_uploaded' => $not_uploaded_files
        ];
    }
}

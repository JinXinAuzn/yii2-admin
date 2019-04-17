<?php

namespace jx\admin\assets;

use yii\web\AssetBundle;

/**
 * @author Au zn <690550322@qq.com>
 * @since Full version
 */
class AppAsset extends AssetBundle
{
	public $sourcePath = '@jx/admin/web';
    public $css = [
	    'css/admin/admin-custom.min.css',
	    'css/admin/skin-custom.min.css',
    ];
    public $depends = [
	    'jx\admin\assets\AdminLteAsset',
    ];
}

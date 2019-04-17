<?php

namespace jx\admin\assets;

use yii\web\AssetBundle;

/**
 * @author Au zn <690550322@qq.com>
 * @since Full version
 */
class AnimateAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
	public $sourcePath = '@jx/admin/web';
    /**
     * @inheritdoc
     */
    public $css = [
        'css/admin/animate.css',
    ];

}

<?php

function routesForModel($model)
{
    return require base_path("routes/Models/{$model}.php");
}

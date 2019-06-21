<?php

namespace RVKolosov\LaravelWithTrait;

use Illuminate\Container\Container;
use Illuminate\Contracts\Auth\Guard;

trait WithTrait
{
    /**
     * @return Container
     */
    protected function getContainer()
    {
        return Container::getInstance();
    }

    /**
     * @return Illuminate\Http\Request;
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function getRequest()
    {
        return $this->getContainer()->make("request");
    }

    /**
     * @return Guard
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function getGuard()
    {
        return $this->getContainer()->make(Guard::class);
    }

    /**
     * @param $model
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function checkPermissions($model)
    {
        $user = $this->getGuard()->user();
        dump($this->getGuard());
        if (!$user->can('index', $model)) {
            abort(404);
        }
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeWithRelations($query)
    {
        $request = $this->getRequest();
        if ($request->has('with') && is_array($request->input('with')))
        {
            foreach ($request->input('with') as $relations)
            {
                if (strlen($relations))
                {
                    $this->hasRelations($relations);
                    $model = $this;
                    $split = explode(".", $relations);
                    foreach($split as $relation) {
                        $class = get_class($model->$relation()->getRelated());
                        // TODO: Add permissions check
    //                    $this->checkPermissions($class);
                        $model = new $class;
                    }

                    $query->with($relations);
                }
            }
        }

        return $query;
    }

    /**
     * @return mixed
     */
    public function scopeLoadRelations()
    {
        $request = $this->getRequest();
        if ($request->has('with') && is_array($request->input('with')))
        {
            $tmp = [];

            foreach ($request->input('with') as $relations)
            {
                if (strlen($relations))
                {
                    $this->hasRelations($relations);
                    $model = $this;
                    $split = explode(".", $relations);
                    foreach($split as $relation) {
                        $class = get_class($model->$relation()->getRelated());
                        // TODO: Add permissions check
    //                    $this->checkPermissions($class);
                        $model = new $class;
                    }

                    $tmp[] = $relations;
                }
            }

            $this->load($tmp);
        }

        return $this;
    }

    /**
     * @param $relations
     */
    private function hasRelations($relations)
    {
        try
        {
            $this->has($relations);
        }
        catch (\Exception $exception)
        {
            if (!config('app.debug'))
            {
                abort(404);
            }
        }
    }
}

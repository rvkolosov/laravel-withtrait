<?php

namespace RVKolosov\LaravelWithTrait;

use Illuminate\Container\Container;
use Illuminate\Contracts\Auth\Guard;

trait WithTrait
{
    /**
     * @return Container
     */
    private function getContainer()
    {
        return Container::getInstance();
    }

    /**
     * @return Illuminate\Http\Request;
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function getRequest()
    {
        return $this->getContainer()->make('request');
    }

    /**
     * @return Guard
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function getGuard()
    {
        return $this->getContainer()->make(Guard::class);
    }

    /**
     * @param $model
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function checkPermission($model)
    {
        $user = $this->getGuard()->user();

        dump($this->getGuard());

        if (!$user->can('index', $model))
        {
            abort(403);
        }
    }

    /**
     * @param $relations
     */
    private function checkPermissions($relations)
    {
        $model = $this;

        foreach(explode('.', $relations) as $relation)
        {
            $class = get_class($model->$relation()->getRelated());
            // TODO: Add permissions check
            // $this->checkPermission($class);
            $model = new $class;
        }
    }

    /**
     * @param $query
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
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

                    $this->checkPermissions($relations);

                    $query->with($relations);
                }
            }
        }

        return $query;
    }

    /**
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
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

                    $this->checkPermissions($relations);

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

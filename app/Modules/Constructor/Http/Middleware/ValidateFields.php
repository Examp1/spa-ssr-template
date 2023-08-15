<?php

namespace App\Modules\Constructor\Http\Middleware;

use Closure;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ValidateFields
{
    /**
     * @var Factory
     */
    protected Factory $validator;

    /**
     * @var Repository
     */
    protected Repository $config;

    /**
     * ValidateFields constructor.
     *
     * @param Factory $validator
     * @param Repository $config
     */
    public function __construct(Factory $validator, Repository $config)
    {
        $this->validator = $validator;

        $this->config = $config;
    }

    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws BindingResolutionException
     * @throws ValidationException
     */
    public function handle(Request $request, Closure $next)
    {
        if (
            ($request->isMethod('post') || $request->isMethod('put'))
            && ($request->has($this->config->get('constructor.fields_name')) && $request->has('constructorData'))
        ) {
//            $messages = ['constructor.1.content.title.max' => '1111111'];

            foreach ($request->get('constructorData') as $lang => $item){
                if(isset($item['entity_name']) && isset($item['constructor'])){
                    $entity = app()->make($item['entity_name']);
                    $entity->entityConstructorId($item['entity_id']);

                    $messages = $entity->messagesConstructorComponentFields($item[$this->config->get('constructor.fields_name')]);

                    $this->validator->make(
                        $item,
                        $entity->rulesConstructorComponentFields($item[$this->config->get('constructor.fields_name')]),
                        $messages
                    )->validate();
                }
            }
        }

        return $next($request);
    }
}

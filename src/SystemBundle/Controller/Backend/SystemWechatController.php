<?php

declare(strict_types=1);
/**
 * This file is part of Hapi.
 *
 * @link     https://www.nasus.top
 * @document https://wiki.nasus.top
 * @contact  xupengfei@xupengfei.net
 * @license  https://github.com/nasustop/hapi/blob/master/LICENSE
 */
namespace SystemBundle\Controller\Backend;

use App\Controller\AbstractController;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpMessage\Exception\BadRequestHttpException;
use Psr\Http\Message\ResponseInterface;
use SystemBundle\Service\SystemWechatService;

class SystemWechatController extends AbstractController
{
    #[Inject]
    protected SystemWechatService $service;

    public function actionEnumDriver(): ResponseInterface
    {
        $data = $this->service->getRepository()->enumDriver();
        return $this->response->success(data: [
            'default' => $this->service->getRepository()->enumDriverDefault(),
            'list' => $data,
        ]);
    }

    public function actionCreate(): ResponseInterface
    {
        $params = $this->request->all();

        $rules = [
            'driver' => 'required',
            'app_id' => 'required',
            'secret' => 'required',
            'token' => 'required',
            'aes_key' => 'required',
        ];
        $messages = [
            'driver.required' => 'driver 参数必填',
            'app_id.required' => 'app_id 参数必填',
            'secret.required' => 'secret 参数必填',
            'token.required' => 'token 参数必填',
            'aes_key.required' => 'aes_key 参数必填',
        ];
        $validator = $this->validatorFactory->make(data: $params, rules: $rules, messages: $messages);

        if ($validator->fails()) {
            throw new BadRequestHttpException(message: $validator->errors()->first());
        }

        $result = $this->service->saveData(data: $params);

        return $this->response->success(data: $result);
    }

    public function actionInfo(): ResponseInterface
    {
        $filter = $this->request->all();
        $result = $this->service->getInfo(filter: $filter);

        return $this->response->success(data: $result);
    }

    public function actionUpdate(): ResponseInterface
    {
        $params = $this->request->all();

        $rules = [
            'filter' => 'required|array',
            'filter.id' => 'required',
            'params' => 'required|array',
            'params.driver' => 'required',
            'params.app_id' => 'required',
            'params.secret' => 'required',
            'params.token' => 'required',
            'params.aes_key' => 'required',
        ];
        $messages = [
            'filter.required' => 'filter 参数必填',
            'filter.array' => 'filter 参数错误，必须是数组格式',
            'filter.id.required' => 'filter.id 参数必填',
            'params.required' => 'filter 参数必填',
            'params.array' => 'filter 参数错误，必须是数组格式',
            'params.driver.required' => 'params.driver 参数必填',
            'params.app_id.required' => 'params.app_id 参数必填',
            'params.secret.required' => 'params.secret 参数必填',
            'params.token.required' => 'params.token 参数必填',
            'params.aes_key.required' => 'params.aes_key 参数必填',
        ];
        $validator = $this->validatorFactory->make(data: $params, rules: $rules, messages: $messages);

        if ($validator->fails()) {
            throw new BadRequestHttpException(message: $validator->errors()->first());
        }

        $result = $this->service->updateOneBy(filter: $params['filter'], data: $params['params']);

        return $this->response->success(data: $result);
    }

    public function actionDelete(): ResponseInterface
    {
        $filter = $this->request->all();
        $result = $this->service->deleteOneBy(filter: $filter);

        return $this->response->success(data: $result);
    }

    public function actionList(): ResponseInterface
    {
        $filter = $this->request->all();
        $page = (int) $this->request->input(key: 'page', default: 1);
        $page_size = (int) $this->request->input(key: 'page_size', default: 20);
        $result = $this->service->pageLists(filter: $filter, columns: '*', page: $page, pageSize: $page_size);

        return $this->response->success(data: $result);
    }
}

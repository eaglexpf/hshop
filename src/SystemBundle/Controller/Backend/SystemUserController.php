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
use SystemBundle\Service\SystemUserService;

class SystemUserController extends AbstractController
{
    #[Inject]
    protected SystemUserService $service;

    public function actionCreate(): ResponseInterface
    {
        $params = $this->request->all();

        $rules = [
            'user_name' => 'required',
            'login_name' => 'required',
            'password' => 'required',
            'mobile' => 'required',
            'user_status' => 'required',
        ];
        $messages = [
            'user_name.required' => 'user_name 参数必填',
            'login_name.required' => 'login_name 参数必填',
            'password.required' => 'password 参数必填',
            'mobile.required' => 'mobile 参数必填',
            'user_status.required' => 'user_status 参数必填',
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
            'filter.user_id' => 'required',
            'params' => 'required|array',
            'params.user_name' => 'required',
            'params.login_name' => 'required',
            'params.password' => 'required',
            'params.mobile' => 'required',
            'params.user_status' => 'required',
        ];
        $messages = [
            'filter.required' => 'filter 参数必填',
            'filter.array' => 'filter 参数错误，必须是数组格式',
            'filter.user_id.required' => 'filter.user_id 参数必填',
            'params.required' => 'filter 参数必填',
            'params.array' => 'filter 参数错误，必须是数组格式',
            'params.user_name.required' => 'params.user_name 参数必填',
            'params.login_name.required' => 'params.login_name 参数必填',
            'params.password.required' => 'params.password 参数必填',
            'params.mobile.required' => 'params.mobile 参数必填',
            'params.user_status.required' => 'params.user_status 参数必填',
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

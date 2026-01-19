<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;

/**
 * OpisanieAiController - генерация описания товара через OpenAI GPT
 */
class OpisanieAiController extends Controller
{
    /**
     * API endpoint (Cloudflare Worker прокси)
     */
    private string $apiUrl = 'https://lucky-butterfly-64ff.as-e62.workers.dev/';
    
    /**
     * Модель GPT
     */
    private string $model = 'gpt-4o-mini';


    /**
     * Генерация описания товара
     * GET /backend/opisanie-ai?name=Название товара
     *
     * @return array
     */
    public function actionIndex()
    {
        $productName = Yii::$app->request->get('name');

        if (empty($productName)) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['error' => 'Параметр "name" обязателен'];
        }

        $prompt = $this->buildPrompt($productName);

        try {
            $response = $this->sendToOpenAI($prompt);
            
            // Возвращаем чистый HTML
            Yii::$app->response->format = Response::FORMAT_HTML;
            return $response;
        } catch (\Exception $e) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Формируем промпт
     *
     * @param string $productName
     * @return string
     */
    private function buildPrompt(string $productName): string
    {
        $format = Yii::$app->request->get('format', 'html');
        
        $formatInstruction = $format === 'markdown' 
            ? 'Форматируй ответ в Markdown. Используй ## для заголовков, - для списков. Не используй HTML теги. ОБЯЗАТЕЛЬНО ставь пустую строку между заголовком и текстом, между абзацами, перед каждым новым заголовком.'
            : 'Форматируй ответ в HTML. Заголовки оборачивай в <h4>, абзацы в <p>, списки в <ul><li>. Весь ответ должен быть в одну строку без переносов.';

        return <<<PROMPT
Создай описание товара «{$productName}»

Требования к структуре текста:
- Заголовок [Описание товара «{$productName}»]: 1-2 абзаца по 1-3 предложения в каждом, которые описывают суть товара (что за прибор, для чего нужен, какие параметры измеряет, как работает, где применяется, какую имеет точность измерения, от чего питается).
- Заголовок [Размеры «{$productName}»]: список с указанием размеров описываемого товара (длина, ширина, высота, вес).
- Заголовок [Основные особенности «{$productName}»]: список с преимуществами и основными особенностями прибора, не включающий его размеры и характеристики. Пример: «Быстрое время отклика», «Высокая точность работы».

В конце описания укажи средний срок службы прибора и гарантийный срок эксплуатации в виде двух отдельных строк.

ВАЖНО: {$formatInstruction}

Требования к формату описания товара:
- Стиль: лаконичный, технический, без маркетинговой «воды».
- Объем: до 1000 знаков, максимум 1500 знаков, если информации по товару много.
PROMPT;
    }

    /**
     * Отправляет запрос к OpenAI API через Cloudflare Worker
     * Тут же указываем описание роли ai агента
     *
     * @param string $prompt
     * @return string
     * @throws \Exception
     */
    private function sendToOpenAI(string $prompt): string
    {
        $data = [
            'model' => $this->model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Ты – специалист по газоанализаторам и сенсорам газа с многолетним опытом. Привык излагать мысли четко, доступно и по делу.'
                ],
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
            'max_tokens' => 1500,
            'temperature' => 0.7,
        ];

        $ch = curl_init($this->apiUrl);
        
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 60,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        
        curl_close($ch);

        if ($error) {
            throw new \Exception("Ошибка подключения: {$error}");
        }

        $result = json_decode($response, true);

        if ($httpCode !== 200) {
            $errorMsg = $result['error']['message'] ?? "HTTP код: {$httpCode}";
            throw new \Exception("Ошибка: {$errorMsg}");
        }

        if (!isset($result['choices'][0]['message']['content'])) {
            throw new \Exception("Неверный формат ответа");
        }

        // Очищаем ответ
        $content = $result['choices'][0]['message']['content'];
        // Убираем markdown code блоки
        $content = preg_replace('/^```html\s*/', '', $content);
        $content = preg_replace('/^```markdown\s*/', '', $content);
        $content = preg_replace('/```$/', '', $content);
        
        // Для HTML убираем переносы, для markdown оставляем
        $format = Yii::$app->request->get('format', 'html');
        if ($format !== 'markdown') {
            $content = str_replace(["\n", "\r"], '', $content);
        }
        $content = trim($content);
        
        return $content;
    }
}

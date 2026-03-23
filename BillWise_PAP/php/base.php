<?php
/*
|--------------------------------------------------------------------------
| Funções base da aplicação
|--------------------------------------------------------------------------
| Centraliza o arranque da sessão, a validação de autenticação, a fuga
| de texto para HTML e alguns utilitários partilhados pelas páginas.
*/

header('Content-Type: text/html; charset=UTF-8');

if (!function_exists('billwise_start_session')) {
    function billwise_start_session(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
}

if (!function_exists('billwise_require_auth')) {
    function billwise_require_auth(): void
    {
        billwise_start_session();

        if (empty($_SESSION['user_id'])) {
            header('Location: index.php');
            exit;
        }
    }
}

if (!function_exists('billwise_escape')) {
    function billwise_escape(?string $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('billwise_user_name')) {
    function billwise_user_name(): string
    {
        billwise_start_session();
        return billwise_escape($_SESSION['name'] ?? 'Utilizador');
    }
}

if (!function_exists('billwise_categories')) {
    function billwise_categories(): array
    {
        return [
            'Alimentação',
            'Transporte',
            'Saúde',
            'Entretenimento',
            'Educação',
            'Outros',
        ];
    }
}

if (!function_exists('billwise_currency')) {
    function billwise_currency(float $value): string
    {
        return '€' . number_format($value, 2, ',', '.');
    }
}
?>

<?php
namespace App\Http\Controllers;

use App\Http\ApiResponse;

abstract class Controller
{
    // regels zoals 'required|string|max:100', stuurt meteen een 422 als er fouten zijn
    protected function validate(array $data, array $rules): void
    {
        $errors = [];

        foreach ($rules as $field => $ruleString) {
            $fieldRules = explode('|', $ruleString);
            $value = $data[$field] ?? null;

            if ($value === null || $value === '') {
                if (in_array('required', $fieldRules)) {
                    $errors[$field] = "Het veld {$field} is verplicht.";
                }
                continue;
            }

            foreach ($fieldRules as $rule) {
                $error = $this->checkRule($field, $value, $rule);

                if ($error !== null) {
                    $errors[$field] = $error;
                    break;
                }
            }
        }

        if (!empty($errors)) {
            ApiResponse::error(ApiResponse::UNPROCESSABLE, 'De invoer is niet geldig.', $errors);
        }
    }

    private function checkRule(string $field, mixed $value, string $rule): ?string
    {
        [$name, $param] = array_pad(explode(':', $rule, 2), 2, null);

        switch ($name) {
            case 'string':
                if (!is_string($value)) {
                    return "Het veld {$field} moet tekst zijn.";
                }
                break;

            case 'int':
                if (filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) === false) {
                    return "Het veld {$field} moet een positief geheel getal zijn.";
                }
                break;

            case 'max':
                if (is_string($value) && mb_strlen($value) > (int) $param) {
                    return "Het veld {$field} mag maximaal {$param} tekens zijn.";
                }
                break;

            // exists:App\Models\ThreadModel -> id moet in die tabel staan (altijd na int zetten)
            case 'exists':
                if ($param::find((int) $value) === null) {
                    return "Het veld {$field} verwijst naar id {$value}, maar die bestaat niet.";
                }
                break;
        }

        return null;
    }
}

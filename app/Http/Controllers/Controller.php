<?php
namespace App\Http\Controllers;

abstract class Controller
{
    // geeft een array met fouten terug, leeg = alles in orde
    protected function validate(array $data, array $rules): array
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
                if ($rule === 'string' && !is_string($value)) {
                    $errors[$field] = "Het veld {$field} moet tekst zijn.";
                    break;
                }

                if ($rule === 'int' && filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) === false) {
                    $errors[$field] = "Het veld {$field} moet een positief geheel getal zijn.";
                    break;
                }

                if (str_starts_with($rule, 'max:')) {
                    $max = (int) substr($rule, 4);

                    if (is_string($value) && mb_strlen($value) > $max) {
                        $errors[$field] = "Het veld {$field} mag maximaal {$max} tekens zijn.";
                        break;
                    }
                }

                if (str_starts_with($rule, 'exists:')) {
                    $modelClass = substr($rule, 7);

                    if ($modelClass::find((int) $value) === null) {
                        $errors[$field] = "Het veld {$field} verwijst naar id {$value}, maar die bestaat niet.";
                        break;
                    }
                }
            }
        }

        return $errors;
    }
}

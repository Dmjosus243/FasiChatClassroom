<?php
namespace Services;

class ValidationService
{
    private array $errors = [];

    public function validate(array $data, array $rules): bool
    {
        $this->errors = [];
        foreach ($rules as $field => $fieldRules) {
            $value = $data[$field] ?? null;
            foreach ($fieldRules as $rule) {
                $this->applyRule($field, $value, $rule);
            }
        }
        return empty($this->errors);
    }

    private function applyRule(string $field, $value, string $rule): void
    {
        if ($rule === 'required' && empty($value)) {
            $this->errors[$field][] = "Le champ $field est requis";
        }
        if ($rule === 'email' && !empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field][] = "Email invalide";
        }
        if (strpos($rule, 'min:') === 0) {
            $min = substr($rule, 4);
            if (strlen($value) < $min) {
                $this->errors[$field][] = "Minimum $min caractères";
            }
        }
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
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
            $this->errors[$field][] = "Le champ $field doit être un email valide";
        }
        
        if (strpos($rule, 'min:') === 0) {
            $min = substr($rule, 4);
            if (strlen($value) < $min) {
                $this->errors[$field][] = "Le champ $field doit contenir au moins $min caractères";
            }
        }
        
        if (strpos($rule, 'max:') === 0) {
            $max = substr($rule, 4);
            if (strlen($value) > $max) {
                $this->errors[$field][] = "Le champ $field ne peut pas dépasser $max caractères";
            }
        }
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
<?php
namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Support\Contracts\Directive;
use Nuwave\Lighthouse\Support\Contracts\FieldMiddleware;
use Nuwave\Lighthouse\Schema\Values\FieldValue;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Closure;
use Illuminate\Support\Facades\Validator;
use GraphQL\Type\Definition\ResolveInfo;
use App\Exceptions\CustomValidationException;

class CustomValidatorDirective implements Directive, FieldMiddleware
{
    public static function definition(): string
    {
        return /* @lang GraphQL */ <<<'GRAPHQL'
            directive @customValidator(rules: [String!]) on FIELD_DEFINITION
        GRAPHQL;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handleField(FieldValue $fieldValue, Closure $next): void
    {
        $rules = $this->directiveArgValue('rules');

        $resolver = $fieldValue->getResolver();

        $fieldValue->setResolver(function ($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo) use ($rules, $resolver) {
            $validator = Validator::make($args, $this->formatRules($rules));

            if ($validator->fails()) {
                throw new CustomValidationException($validator);
            }

            return $resolver($root, $args, $context, $resolveInfo);
        });

        $next($fieldValue);
    }

    protected function formatRules(array $rules): array
    {
        return collect($rules)->mapWithKeys(function ($rule) {
            [$field, $rule] = explode(':', $rule, 2);
            return [$field => explode('|', $rule)];
        })->toArray();
    }
}

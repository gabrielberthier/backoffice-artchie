something that could be pretty neat would be having an array of validations as

rules:
'field' => fn($validator) => $validator->rule()->rule2();

messages:
'field' => 'message'

or

class Validator(field){
fun validate(){
return true|false
}
}

Important:
Validation should return errorBag
Validation should be thrown

0800 021 3004

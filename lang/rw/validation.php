<?php

return [

    'accepted' => ':attribute igomba kwemewa.',
    'accepted_if' => ':attribute igomba kwemewa igihe :other ari :value.',
    'active_url' => ':attribute igomba kuba URL yemewe.',
    'after' => ':attribute igomba kuba itariki iri nyuma ya :date.',
    'after_or_equal' => ':attribute igomba kuba itariki iri nyuma cyangwa ingana na :date.',
    'alpha' => ':attribute igomba kuba irimo inyuguti gusa.',
    'alpha_dash' => ':attribute igomba kuba irimo inyuguti, imibare, udushya n’utunyuguti.',
    'alpha_num' => ':attribute igomba kuba irimo inyuguti n’imibare gusa.',
    'any_of' => ':attribute si iyemewe.',
    'array' => ':attribute igomba kuba urutonde.',
    'ascii' => ':attribute igomba kuba irimo inyuguti n’ibimenyetso bya ASCII gusa.',
    'before' => ':attribute igomba kuba itariki iri mbere ya :date.',
    'before_or_equal' => ':attribute igomba kuba itariki iri mbere cyangwa ingana na :date.',
    'between' => [
        'array' => ':attribute igomba kugira ibintu biri hagati ya :min na :max.',
        'file' => ':attribute igomba kuba hagati ya :min na :max KB.',
        'numeric' => ':attribute igomba kuba hagati ya :min na :max.',
        'string' => ':attribute igomba kuba hagati ya inyuguti :min na :max.',
    ],
    'boolean' => ':attribute igomba kuba "ukuri" cyangwa "ikinyoma".',
    'can' => ':attribute ifite agaciro katemewe.',
    'confirmed' => 'Kwemeza :attribute ntibihuye.',
    'contains' => ':attribute irimo agaciro kabura.',
    'current_password' => 'Ijambobanga si ryo.',
    'date' => ':attribute igomba kuba itariki yemewe.',
    'date_equals' => ':attribute igomba kuba itariki ingana na :date.',
    'date_format' => ':attribute igomba guhura n’ifishi ya :format.',
    'decimal' => ':attribute igomba kugira imibare :decimal inyuma y’akadomo.',
    'declined' => ':attribute igomba kwangwa.',
    'declined_if' => ':attribute igomba kwangwa igihe :other ari :value.',
    'different' => ':attribute na :other bigomba gutandukana.',
    'digits' => ':attribute igomba kuba imibare :digits.',
    'digits_between' => ':attribute igomba kuba hagati ya :min na :max imibare.',
    'dimensions' => ':attribute ifite ingano y’ifoto itemewe.',
    'distinct' => ':attribute irimo agaciro kabiri kangana.',
    'doesnt_end_with' => ':attribute ntigomba kurangira na kimwe muri ibi: :values.',
    'doesnt_start_with' => ':attribute ntigomba gutangira na kimwe muri ibi: :values.',
    'email' => ':attribute igomba kuba aderesi email yemewe.',
    'ends_with' => ':attribute igomba kurangira na kimwe muri ibi: :values.',
    'enum' => ':attribute yatoranijwe si iyemewe.',
    'exists' => ':attribute yatoranijwe si iyemewe.',
    'extensions' => ':attribute igomba kugira imwe muri izi nyongera: :values.',
    'file' => ':attribute igomba kuba dosiye.',
    'filled' => ':attribute igomba kugira agaciro.',
    'gt' => [
        'array' => ':attribute igomba kugira ibintu birenze :value.',
        'file' => ':attribute igomba kuba irenze :value KB.',
        'numeric' => ':attribute igomba kuba irenze :value.',
        'string' => ':attribute igomba kuba irenze inyuguti :value.',
    ],
    'gte' => [
        'array' => ':attribute igomba kugira ibintu bingana cyangwa birenze :value.',
        'file' => ':attribute igomba kuba ingana cyangwa irenze :value KB.',
        'numeric' => ':attribute igomba kuba ingana cyangwa irenze :value.',
        'string' => ':attribute igomba kuba ingana cyangwa irenze inyuguti :value.',
    ],
    'image' => ':attribute igomba kuba ifoto.',
    'in' => ':attribute yatoranijwe si iyemewe.',
    'in_array' => ':attribute igomba kubaho muri :other.',
    'integer' => ':attribute igomba kuba umubare wuzuye.',
    'list' => ':attribute igomba kuba urutonde.',
    'lowercase' => ':attribute igomba kuba inyuguti nto.',
    'lt' => [
        'array' => ':attribute igomba kugira ibintu biri munsi ya :value.',
        'file' => ':attribute igomba kuba munsi ya :value KB.',
        'numeric' => ':attribute igomba kuba munsi ya :value.',
        'string' => ':attribute igomba kuba munsi y’inyuguti :value.',
    ],
    'lte' => [
        'array' => ':attribute ntigomba kugira ibintu birenze :value.',
        'file' => ':attribute igomba kuba munsi cyangwa ingana na :value KB.',
        'numeric' => ':attribute igomba kuba munsi cyangwa ingana na :value.',
        'string' => ':attribute igomba kuba munsi cyangwa ingana n’inyuguti :value.',
    ],
    'max' => [
        'array' => ':attribute ntigomba kugira ibintu birenze :max.',
        'file' => ':attribute ntigomba kurenza :max KB.',
        'numeric' => ':attribute ntigomba kurenza :max.',
        'string' => ':attribute ntigomba kurenza inyuguti :max.',
    ],
    'max_digits' => ':attribute ntigomba kugira imibare irenze :max.',
    'mimes' => ':attribute igomba kuba dosiye yo mu bwoko: :values.',
    'mimetypes' => ':attribute igomba kuba dosiye yo mu bwoko: :values.',
    'min' => [
        'array' => ':attribute igomba kugira nibura ibintu :min.',
        'file' => ':attribute igomba kuba nibura :min KB.',
        'numeric' => ':attribute igomba kuba nibura :min.',
        'string' => ':attribute igomba kuba nibura inyuguti :min.',
    ],
    'min_digits' => ':attribute igomba kugira nibura imibare :min.',
    'missing' => ':attribute igomba kubura.',
    'missing_if' => ':attribute igomba kubura igihe :other ari :value.',
    'missing_unless' => ':attribute igomba kubura keretse :other ari :value.',
    'missing_with' => ':attribute igomba kubura igihe :values ibonetse.',
    'missing_with_all' => ':attribute igomba kubura igihe :values zose zibonetse.',
    'multiple_of' => ':attribute igomba kuba inshuro ya :value.',
    'not_in' => ':attribute yatoranijwe si iyemewe.',
    'not_regex' => 'Imiterere ya :attribute si iyemewe.',
    'numeric' => ':attribute igomba kuba umubare.',
    'password' => [
        'letters' => ':attribute igomba kugira inyuguti nibura.',
        'mixed' => ':attribute igomba kugira inyuguti nkuru n’inyuguti nto.',
        'numbers' => ':attribute igomba kugira umubare nibura.',
        'symbols' => ':attribute igomba kugira ikimenyetso nibura.',
        'uncompromised' => ':attribute yatanze umutekano muke. Hitamo indi.',
    ],
    'present' => ':attribute igomba kuboneka.',
    'present_if' => ':attribute igomba kuboneka igihe :other ari :value.',
    'present_unless' => ':attribute igomba kuboneka keretse :other ari :value.',
    'present_with' => ':attribute igomba kuboneka igihe :values ibonetse.',
    'present_with_all' => ':attribute igomba kuboneka igihe :values zose zibonetse.',
    'prohibited' => ':attribute irabujijwe.',
    'prohibited_if' => ':attribute irabujijwe igihe :other ari :value.',
    'prohibited_if_accepted' => ':attribute irabujijwe igihe :other yemerewe.',
    'prohibited_if_declined' => ':attribute irabujijwe igihe :other yanze.',
    'prohibited_unless' => ':attribute irabujijwe keretse :other iri muri :values.',
    'prohibits' => ':attribute irabuza :other kuboneka.',
    'regex' => 'Imiterere ya :attribute si iyemewe.',
    'required' => ':attribute irakenewe.',
    'required_array_keys' => ':attribute igomba kugira ibyinjijwe kuri: :values.',
    'required_if' => ':attribute irakenewe igihe :other ari :value.',
    'required_if_accepted' => ':attribute irakenewe igihe :other yemerewe.',
    'required_if_declined' => ':attribute irakenewe igihe :other yanze.',
    'required_unless' => ':attribute irakenewe keretse :other iri muri :values.',
    'required_with' => ':attribute irakenewe igihe :values ibonetse.',
    'required_with_all' => ':attribute irakenewe igihe :values zose zibonetse.',
    'required_without' => ':attribute irakenewe igihe :values itabonetse.',
    'required_without_all' => ':attribute irakenewe igihe nta n’imwe muri :values ibonetse.',
    'same' => ':attribute igomba guhura na :other.',
    'size' => [
        'array' => ':attribute igomba kugira ibintu :size.',
        'file' => ':attribute igomba kuba :size KB.',
        'numeric' => ':attribute igomba kuba :size.',
        'string' => ':attribute igomba kuba inyuguti :size.',
    ],
    'starts_with' => ':attribute igomba gutangira na kimwe muri ibi: :values.',
    'string' => ':attribute igomba kuba inyuguti.',
    'timezone' => ':attribute igomba kuba agace k’igihe kemewe.',
    'unique' => ':attribute yagombaga kuba yihariye.',
 


    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];

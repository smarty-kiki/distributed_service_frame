@php
$otherwise_infos = [];
$param_infos = [];
$setting_lines = [];
foreach ($relationship_infos['relationships'] as $attribute_name => $relationship) {
    $entity = $relationship['entity'];
    if ($relationship['relationship_type'] === 'belongs_to') {
        if ($relationship['association_type'] === 'composition') {
            $param_infos[] = "$$attribute_name";
            $otherwise_infos[] = "otherwise($$attribute_name instanceof $entity, '$$attribute_name 传入了无效的 $entity')";
        } else {
            $setting_lines[] = "$$entity_name->$attribute_name = \$optional_structs['$attribute_name']";
        }
    }
}
foreach ($entity_info['structs'] as $struct_name => $struct) {

    if ($struct['require']) {
        $param_infos[] = "$$struct_name";
    } else {
        $setting_lines[] = "$$entity_name->$struct_name = \$optional_structs['$struct_name']";
    }
}
$service_param_infos = $param_infos;
if ($setting_lines) {
    $service_param_infos[] = '$optional_structs = []';
}
@endphp
@if ($service_param_infos)
service('{{ $entity_name }}@create', function (
    {{ implode(",\n    ", $service_param_infos)."\n" }}
) {/*{^^{^^{*/
@else
service('{{ $entity_name }}@create', function ()
{/*{^^{^^{*/
@endif
@foreach ($otherwise_infos as $otherwise_info)
    {{ $otherwise_info }};
@endforeach
@if ($entity_info['repeat_check_structs'])
@php
$repeat_check_structs = $entity_info['repeat_check_structs'];
$dao_param_infos = [];
$msg_infos = [];
foreach ($repeat_check_structs as $struct_name) {
    $dao_param_infos[] = "$$struct_name";
    $msg_infos[] = $entity_info['structs'][$struct_name]['display_name'];
}
@endphp

    $another_{{ $entity_name }} = dao('{{ $entity_name }}')->find_by_{{ implode('_and_', $repeat_check_structs) }}({{ implode(', ', $dao_param_infos) }});
    otherwise($another_{{ $entity_name }}->is_null(), '已经存在相同{{ implode('和', $msg_infos) }}的{{ $entity_info['display_name'] }} [ID: '.$another_{{ $entity_name }}->id.']');
@endif

@if (empty($param_infos))
    ${{ $entity_name }} = {{ $entity_name }}::create();
@else
    ${{ $entity_name }} = {{ $entity_name }}::create(
        {{ implode(",\n        ", $param_infos)."\n" }}
    );
@endif
@if (! empty($setting_lines))

@foreach ($setting_lines as $setting_line)
    {{ $setting_line }};
@endforeach
@endif

    return ${{ $entity_name }};
});/*}}}*/

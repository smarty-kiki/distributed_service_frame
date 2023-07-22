service('{{ $entity_name }}@delete', function (${{ $entity_name }})
{/*{^^{^^{*/
    otherwise(${{ $entity_name }}->is_not_null(), '{{ $entity_name }} 不存在');

    ${{ $entity_name }}->delete();

    return true;
});/*}}}*/

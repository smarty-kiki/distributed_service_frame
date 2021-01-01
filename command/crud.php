<?php

function _generate_service_file($entity_name, $entity_info, $relationship_infos)
{/*{{{*/
    $content = _get_service_template_from_extension('add');

    otherwise($content, '没找到 service 的 add 模版');

    $add_content =  blade_eval($content, [
        'entity_name' => $entity_name,
        'entity_info' => $entity_info,
        'relationship_infos' => $relationship_infos,
    ]);

    $content = _get_service_template_from_extension('update');

    otherwise($content, '没找到 service 的 update 模版');

    $update_content =  blade_eval($content, [
        'entity_name' => $entity_name,
        'entity_info' => $entity_info,
        'relationship_infos' => $relationship_infos,
    ]);

    $content = _get_service_template_from_extension('delete');

    otherwise($content, '没找到 service 的 delete 模版');

    $delete_content =  blade_eval($content, [
        'entity_name' => $entity_name,
        'entity_info' => $entity_info,
        'relationship_infos' => $relationship_infos,
    ]);

    $template = "<?php

%s
%s
%s";

    $content = sprintf($template, $add_content, $update_content, $delete_content);

    return str_replace('^^', '', $content);
}/*}}}*/

function _generate_service_data_type_add($data_type)
{/*{{{*/
    $content = _get_data_type_service_from_extension('add', $data_type);

    otherwise($content, '没找到 '.$data_type.' 的 add 模版');

    return $content;
}/*}}}*/

function _generate_service_data_type_update($data_type)
{/*{{{*/
    $content = _get_data_type_service_from_extension('update', $data_type);

    otherwise($content, '没找到 '.$data_type.' 的 update 模版');

    return $content;
}/*}}}*/

command('crud:make-from-description', '通过描述文件生成 CRUD 控制器', function ()
{/*{{{*/
    $entity_names = _get_entity_name_by_command_paramater();

    foreach ($entity_names as $entity_name) {

        $entity_info = description_get_entity($entity_name);

        $relationship_infos = description_get_relationship_with_snaps_by_entity($entity_name);

        $service_file_string = _generate_service_file($entity_name, $entity_info, $relationship_infos);

        // 写文件
        error_log($service_file_string, 3, $service_file = SERVICE_DIR.'/'.$entity_name.'.php');
        echo "generate $service_file success!\n";
        echo "todo ".ROOT_DIR."/public/index.php include $service_file\n";
    }
});/*}}}*/

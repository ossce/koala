<?php
 goto A3YHh; mNoha: l7cRc: goto l6Xe8; kGmZW: e4EvE: goto uUwoc; Zrk_w: $footnav_index = count($module_list) - 1; goto on2wm; LTpSi: foreach ($tpl_item["\x64\141\164\141"] as $v) { goto MIW2e; AXg7U: $editor_data[] = <<<EOF
\t\t\t\t\t\t\t\tDiy.Editor.data['{$v["\147\165\x69\144"]}'] = {$str};
EOF;
goto QEimS; QEimS: Ega9L: goto zLw5n; MIW2e: $str = json_encode($v); goto AXg7U; zLw5n: } goto Kh1FU; iclpL: $diy_data = array("\165\x6e\151\141\143\151\x64" => $uniacid, "\x63\157\x76\x65\x72" => trim($_GPC["\143\x6f\166\x65\x72"]), "\x70\141\147\145\137\x74\151\x74\154\x65" => trim($_GPC["\x70\x61\x67\x65\137\164\151\x74\x6c\x65"]), "\144\141\164\x61" => json_encode($data), "\x75\160\144\141\164\145\137\164\151\x6d\x65" => time()); goto jEXOy; vaaM4: goto H63sY; goto M_bZm; EYivN: $data = json_decode($data, true); goto H3poN; UnV3L: $tpl_item = pdo_get($this->table_shop_diy_template, array("\x75\x6e\x69\141\143\151\x64" => $uniacid, "\x69\x64" => $id)); goto LqL5b; q3d05: array_multisort($displayorders, SORT_ASC, $data); goto PVuxf; IVxwf: $diy_data["\x70\x61\x67\x65\x5f\164\x79\160\145"] = $tpl_item["\x70\x61\x67\145\x5f\164\x79\x70\x65"]; goto JDYfg; Isu8N: $handle_type = 3; goto A9Opn; YjE5_: $category_list = $shop_common->getCategoryList(); goto J2axZ; MGUNT: FQzJo: goto Cp4U9; M_bZm: pueJz: goto AUxyj; C0Mm2: OgmSA: goto jlmiX; z6bJ8: goto H63sY; goto kGmZW; OBbAW: foreach ($data as $k => $v) { goto RCD6f; S5bxa: goto G5ZIi; goto DnNbH; GICx2: G5ZIi: goto MIlQi; RCD6f: if (!(empty($v["\x6e\x61\x6d\x65"]) && empty($v["\x67\x75\151\144"]))) { goto amuIO; } goto nrLw5; DnNbH: amuIO: goto GICx2; nrLw5: unset($data[$k]); goto S5bxa; MIlQi: } goto xjlof; Pjf3t: $block_tpl = $diy_template->getDiyTemplate("\x62\x6c\x6f\x63\153"); goto LLvbc; A3YHh: include_once dirname(__FILE__) . "\x2f\x2e\x2e\57\56\x2e\57\x63\157\x72\x65\x2f\x44\x69\171\x54\x65\155\160\154\141\164\145\56\160\150\x70"; goto AtQkx; CwNPy: $tpl_item["\x64\x61\x74\x61"] = json_decode($tpl_item["\x64\x61\164\141"], true); goto Rcp9d; yyGu5: $output_data = array("\143\x6f\x64\145" => -1, "\x65\x72\x72\155\163\147" => "\346\x82\xa8\350\xbf\230\xe6\xb2\241\xe6\234\211\xe6\xb7\273\xe5\212\xa0\344\273\xbb\xe4\xbd\x95\xe7\xbb\204\344\273\266"); goto uDJLJ; QYVYH: if (!checksubmit("\x73\165\x62\x6d\x69\x74")) { goto ozw1a; } goto Hu7tw; IJcof: VApdV: goto CwNPy; TiNKW: pTLsI: goto bnFBp; f6fB9: goto FQzJo; goto C0Mm2; sWD12: $res = pdo_insert($this->table_shop_diy_template, $diy_data); goto X6dav; CsHkj: $handle_type = 1; goto EpEXn; qV4el: $page_type = $tpl_item["\x70\x61\147\x65\x5f\164\x79\160\x65"] ? $tpl_item["\x70\141\x67\145\x5f\x74\171\x70\145"] : $_GPC["\160\141\147\x65\137\164\171\x70\145"]; goto ABlIs; QlXMB: $editor_data = implode('', $editor_data); goto AHHpE; Rcp9d: if (empty($tpl_item["\144\141\x74\x61"])) { goto IRxr3; } goto LTpSi; TNDDh: $output_data = array("\143\157\144\x65" => -1, "\145\x72\162\x6d\163\x67" => "\xe4\277\x9d\345\xad\230\xe5\xa4\261\xe8\xb4\xa5\357\274\x8c\xe8\xaf\267\347\xa8\x8d\345\220\x8e\xe9\x87\x8d\xe8\257\225"); goto KEk5r; Qwsta: $id = intval($_GPC["\x69\x64"]); goto YGmtZ; H3poN: if (!empty($data)) { goto pTLsI; } goto yyGu5; PVuxf: if (empty($data)) { goto owJhW; } goto OBbAW; EpEXn: $handle_font = "\346\xb7\273\xe5\x8a\xa0"; goto a6p5T; PVQru: if ($diy_data["\160\x61\147\145\137\x74\x79\x70\145"] == 2) { goto e4EvE; } goto vaaM4; KEk5r: $this->resultJson($output_data); goto f6fB9; LqL5b: if (!empty($tpl_item)) { goto VApdV; } goto kvZvB; NNxOi: owJhW: goto iclpL; A9Opn: $handle_font = "\347\274\226\350\276\221"; goto aEqfs; l6Xe8: $diy_data["\x70\x61\x67\x65\137\x74\x79\160\x65"] = intval($_GPC["\160\x61\147\x65\137\164\x79\x70\x65"]); goto xtCW4; kvZvB: message("\346\250\xa1\346\235\277\xe4\270\x8d\345\255\x98\xe5\234\xa8"); goto IJcof; AHHpE: IRxr3: goto kt_21; X6dav: $id = pdo_insertid(); goto CsHkj; VGTbY: $module_list = $diy_template->getModuleList(); goto Pjf3t; jlmiX: if ($diy_data["\x70\141\x67\x65\x5f\164\171\160\145"] == 1) { goto pueJz; } goto PVQru; J2axZ: $diy_template = new DiyTemplate(); goto VGTbY; AtQkx: $goodsStatusList = $typeStatus->shopGoodsStatusList(); goto YjE5_; xjlof: k65gi: goto NNxOi; Kh1FU: wBJoh: goto QlXMB; uDJLJ: $this->resultJson($output_data); goto TiNKW; Hu7tw: $data = htmlspecialchars_decode($_GPC["\x64\x61\x74\141"], ENT_QUOTES); goto EYivN; JDYfg: $res = pdo_update($this->table_shop_diy_template, $diy_data, array("\165\156\151\141\143\151\x64" => $uniacid, "\151\x64" => $id)); goto Isu8N; jEXOy: if (empty($tpl_item)) { goto l7cRc; } goto IVxwf; LKm_q: $this->resultJson($output_data); goto MGUNT; d3KG7: if ($res) { goto OgmSA; } goto TNDDh; aEqfs: goto qwO5T; goto mNoha; R3xgR: $shop_common->addSysLog($_W["\x75\151\144"], $_W["\x75\163\145\162\x6e\x61\x6d\145"], $handle_type, "\345\x9f\272\xe6\234\xac\350\xae\276\347\xbd\xae\55\76\351\xa1\xb5\xe9\x9d\xa2\xe6\xa8\241\346\x9d\xbf", "{$handle_font}\xe6\211\213\346\234\xba\xe7\253\257\xe6\xa8\241\xe6\x9d\277\50{$id}\51"); goto K27Hg; LLvbc: $editor_tpl = $diy_template->getDiyTemplate("\x65\144\151\x74\157\162"); goto Qwsta; bnFBp: $displayorders = array_column($data, "\144\x69\x73\160\x6c\x61\171\157\162\x64\145\162"); goto q3d05; uUwoc: cache_delete("\x66\x79\x5f\154\x65\x73\163\x6f\x6e\137" . $uniacid . "\137\x73\150\157\x70\x5f\x64\151\x79\x5f\x74\145\155\x70\x6c\x61\164\145\x5f" . $id); goto OP4ZG; OP4ZG: H63sY: goto R3xgR; kt_21: xZcNL: goto qV4el; YGmtZ: if (empty($id)) { goto xZcNL; } goto UnV3L; xtCW4: $diy_data["\x61\x64\144\x74\151\x6d\145"] = time(); goto sWD12; AUxyj: cache_delete("\146\x79\x5f\x6c\x65\163\163\x6f\x6e\x5f" . $uniacid . "\x5f\x73\150\157\160\137\144\151\171\x5f\164\x65\x6d\160\x6c\x61\x74\x65"); goto z6bJ8; on2wm: unset($module_list[$footnav_index]); goto oL5C7; ABlIs: if (!($page_type != 1)) { goto RS8D2; } goto Zrk_w; K27Hg: $output_data = array("\x63\157\x64\x65" => 0, "\x69\144" => $id, "\145\x72\x72\155\x73\147" => "\344\xbf\x9d\345\255\x98\346\x88\220\xe5\212\x9f", "\x62\x61\143\153\x75\162\154" => $_W["\x73\151\164\145\162\x6f\x6f\x74"] . "\x77\x65\x62\57" . str_replace("\56\57", '', $this->createWebUrl("\x73\145\x74\x74\x69\x6e\147", array("\157\160" => "\x61\144\144\104\x69\x79", "\x69\x64" => $id)))); goto LKm_q; oL5C7: RS8D2: goto QYVYH; a6p5T: qwO5T: goto d3KG7; Cp4U9: ozw1a:
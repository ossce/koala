<?php
 goto A0HjF; c5p9G: itoast("\xe6\x89\xb9\351\207\217\346\216\222\345\xba\x8f\xe6\x88\x90\345\212\237", '', "\163\x75\143\143\x65\163\163"); goto X17aG; xTpHt: if (!is_array($_GPC["\x64\x69\163\x70\154\x61\171\157\x72\144\145\162"])) { goto PuF7o; } goto jTND3; WJgNW: $list = pdo_fetchall("\123\105\x4c\105\103\x54\40\x2a\40\x46\122\x4f\x4d\x20" . tablename($this->table_shop_attr) . "\40\x57\x48\105\122\105\40\165\156\151\141\x63\x69\144\75\x3a\165\156\x69\x61\143\x69\x64\x20\x4f\x52\x44\x45\x52\x20\x42\x59\40\x64\x69\x73\x70\x6c\x61\x79\x6f\x72\144\x65\162\40\x44\105\123\x43\54\x20\151\144\x20\104\x45\123\103\x20\x4c\111\115\x49\124\x20" . ($pindex - 1) * $psize . "\54" . $psize, array("\72\x75\x6e\151\x61\x63\151\144" => $uniacid)); goto kTZlU; notan: s9mjE: goto ylc_r; ylc_r: $total = pdo_fetchcolumn("\123\105\x4c\x45\x43\124\40\x43\117\x55\116\124\50\52\x29\x20\x46\122\x4f\x4d\40" . tablename($this->table_shop_attr) . "\x20\x57\x48\x45\122\105\40\x75\156\151\141\143\x69\144\x3d\72\165\x6e\151\x61\143\151\144", array("\x3a\x75\156\151\141\x63\151\x64" => $uniacid)); goto aRqwN; X17aG: Ukog8: goto WJgNW; nK_XY: gw18B: goto wFM3T; wFM3T: PuF7o: goto c5p9G; A0HjF: if (!checksubmit("\x73\165\142\x6d\151\164\117\x72\144\145\x72")) { goto Ukog8; } goto xTpHt; jTND3: foreach ($_GPC["\144\x69\163\x70\x6c\141\171\x6f\x72\x64\145\x72"] as $k => $v) { goto jwzYE; CHbn1: eG4lI: goto TJsZ2; vTUDK: pdo_update($this->table_shop_attr, $data, array("\165\x6e\151\141\x63\151\x64" => $uniacid, "\x69\x64" => $k)); goto CHbn1; jwzYE: $data = array("\x64\151\x73\160\154\141\171\x6f\162\144\x65\x72" => intval($v)); goto vTUDK; TJsZ2: } goto nK_XY; kTZlU: foreach ($list as $k => $v) { goto UMB1N; N205o: foreach ($value_list as $k1 => $v1) { $values .= $v1["\x76\141\154\x75\145"] . "\xef\274\x8c"; AAlv6: } goto Pyf0y; UMB1N: $value_list = pdo_fetchall("\123\105\x4c\105\103\x54\40\166\x61\154\x75\x65\x20\106\x52\x4f\x4d\x20" . tablename($this->table_shop_value) . "\x20\127\x48\105\x52\105\40\165\x6e\151\141\x63\x69\x64\75\x3a\x75\x6e\x69\141\143\x69\144\x20\101\116\x44\40\141\164\x74\162\137\151\144\75\x3a\x61\164\164\x72\137\151\x64\x20\117\122\104\x45\x52\40\102\x59\x20\144\151\163\x70\154\141\x79\157\162\x64\145\x72\x20\104\105\x53\x43\x2c\x20\x69\x64\40\x41\x53\103\x20", array("\x3a\165\x6e\151\141\143\151\x64" => $uniacid, "\x3a\141\164\x74\162\137\151\x64" => $v["\x69\x64"])); goto hPNMM; hPNMM: $values = ''; goto N205o; q5ykb: C1_a_: goto nA5lt; ZzW7m: $v["\166\141\x6c\x75\x65\x73"] = trim($values, "\xef\274\214"); goto Z0maj; Z0maj: $list[$k] = $v; goto q5ykb; Pyf0y: nso9v: goto ZzW7m; nA5lt: } goto notan; aRqwN: $pager = pagination($total, $pindex, $psize);
<?php
 goto V9BSy; Q6QI0: $starttime = strtotime($_GPC["\x74\151\155\145"]["\x73\164\x61\162\x74"]); goto IDURp; BluSK: $params["\x3a\145\156\144\x74\x69\x6d\x65"] = $endtime; goto IPKG3; r2FzL: hsRdK: goto Q6QI0; IDURp: $endtime = strtotime($_GPC["\164\151\155\145"]["\145\156\144"]) + 86399; goto Kqlh1; VA9eb: QkVVX: goto R32t2; C4EYG: foreach ($list as $k => $v) { goto BCiYK; YPQrq: $total_integral[$k] = $v["\164\x6f\x74\141\x6c\137\151\156\x74\x65\x67\162\x61\x6c"]; goto MA5ax; BCiYK: $title[$k] = $v["\164\x69\164\x6c\x65"]; goto vP_uE; vP_uE: $total[$k] = $v["\x74\157\164\141\x6c"]; goto qojtu; MA5ax: N7xT_: goto jYvau; qojtu: $total_price[$k] = $v["\x74\157\164\141\x6c\x5f\x70\x72\151\143\x65"]; goto YPQrq; jYvau: } goto VA9eb; btwwH: $params["\x3a\163\164\x61\x72\164\164\x69\x6d\145"] = $starttime; goto BluSK; xBA3D: $endtime = strtotime("\164\157\144\141\171") + 86399; goto FplvT; V7FIa: $list = pdo_fetchall("\123\x45\114\x45\103\124\40\164\151\x74\154\x65\x2c\x43\117\x55\x4e\x54\x28\164\157\x74\141\x6c\x29\40\x41\x53\x20\x74\157\164\141\x6c\x2c\123\x55\x4d\50\160\x72\x69\143\x65\52\x74\157\x74\141\154\51\x20\101\123\x20\x74\157\164\141\154\137\x70\162\151\143\x65\54\x53\x55\115\x28\151\x6e\164\x65\147\x72\141\154\x2a\164\x6f\x74\x61\154\51\x20\101\123\x20\164\157\164\x61\x6c\x5f\151\156\164\145\147\162\x61\x6c\40\106\122\117\x4d\x20" . tablename($this->table_shop_order_goods) . "\x20\127\x48\x45\122\105\40{$condition}\x20\107\122\117\125\120\x20\x42\131\x20\164\x69\164\154\145\x20\117\x52\104\x45\x52\40\102\131\x20\x74\157\x74\141\x6c\137\160\x72\x69\143\145\x20\104\105\123\103\54\164\157\x74\x61\154\137\x69\x6e\164\x65\147\x72\141\x6c\40\104\105\x53\103\54\x74\x6f\x74\x61\154\40\x44\x45\123\103\x20\x4c\111\115\x49\124\x20\x30\x2c\x31\60", $params); goto QD3lP; IPKG3: $params["\72\163\x74\x61\x74\165\163"] = 0; goto V7FIa; aWrTQ: if (!$list) { goto GWbDM; } goto C4EYG; CKYdM: $params["\x3a\165\x6e\x69\141\143\151\x64"] = $uniacid; goto btwwH; F31AH: $starttime = strtotime("\55\61\x20\155\x6f\156\164\150"); goto xBA3D; Kqlh1: VGYhT: goto vjOGx; V9BSy: if (strtotime($_GPC["\164\151\x6d\x65"]["\x73\164\x61\x72\x74"]) && strtotime($_GPC["\x74\x69\155\x65"]["\145\x6e\144"])) { goto hsRdK; } goto F31AH; vjOGx: $condition = "\x20\x75\156\151\x61\x63\x69\144\75\x3a\165\x6e\151\141\x63\151\x64\x20\x41\x4e\104\40\x61\x64\144\164\151\155\145\76\x3d\x3a\x73\164\x61\162\164\x74\151\155\145\x20\101\x4e\104\40\x61\x64\x64\164\x69\155\x65\74\75\72\145\x6e\x64\164\151\x6d\145\x20\101\x4e\x44\x20\163\164\x61\164\x75\163\76\x3a\x73\x74\141\164\165\163\x20"; goto CKYdM; QD3lP: $titles = $total = $total_price = $total_integral = []; goto aWrTQ; FplvT: goto VGYhT; goto r2FzL; R32t2: GWbDM:
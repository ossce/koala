<?php
 goto FwN9c; keiCD: $this->checkdir($path); goto qOgix; ci2wl: hy8Dg: goto nbiAe; iGlAx: $this->checkdir($path); goto mKx75; lXnNo: $res["\x70\141\164\x68"] = str_replace("\56\56\x2f\x61\x74\x74\x61\x63\x68\155\145\x6e\164\57", '', $new_file); goto VqFp4; VAzrl: $new_file = $path . random(30) . "\56{$type}"; goto Hwsdx; nbiAe: $type = $result[2]; goto VAzrl; RP6W8: KIV_a: goto xrIqX; kgdP8: if (empty($_W["\x73\x65\x74\164\x69\156\x67"]["\x72\x65\155\157\164\145"]["\x74\171\160\x65"])) { goto KIV_a; } goto Coqpk; Hwsdx: if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $_GPC["\x69\x6d\141\x67\x65\104\x61\x74\141"])))) { goto kgysF; } goto g1d1G; yLERN: dFRqM: goto O0ObB; VqFp4: Q2TBv: goto yLERN; Coqpk: $remotestatus = file_remote_upload($res["\160\141\164\150"]); goto XX9XN; DqcFY: j23DJ: goto RP6W8; li5wL: $path = "\56\56\x2f\141\x74\164\141\x63\150\155\145\x6e\x74\57\151\x6d\x61\147\x65\x73\x2f{$uniacid}\x2f"; goto keiCD; voAB3: $this->checkdir($path); goto ck7Dp; tnNV3: goto dFRqM; goto ci2wl; g1d1G: $this->resultJson("\345\x9b\276\347\x89\x87\346\225\xb0\xe6\215\256\xe9\x94\x99\xe8\xaf\xaf\357\xbc\214\350\257\xb7\351\207\215\350\xaf\x95"); goto JT2Xt; FwN9c: load()->func("\146\x69\x6c\145"); goto RYlj7; O0ObB: llMyZ: goto kgdP8; xrIqX: $data = array("\165\x72\154" => $_W["\141\x74\x74\141\143\x68\165\162\154"] . $res["\x70\x61\164\x68"], "\x73\162\x63" => $res["\160\141\164\x68"]); goto iUhpy; OoonM: $this->resultJson("\xe5\233\276\347\211\207\346\225\260\346\215\256\351\224\x99\xe8\257\xaf\357\xbc\x8c\xe8\257\xb7\351\207\215\350\xaf\225"); goto tnNV3; RYlj7: if (!($_GPC["\x74\x79\x70\x65"] == "\x62\x61\x73\x65\x36\x34")) { goto llMyZ; } goto li5wL; mKx75: $path .= date("\x6d", time()) . "\57"; goto voAB3; ck7Dp: if (preg_match("\57\136\x28\144\141\x74\141\x3a\x5c\x73\x2a\151\x6d\141\147\x65\x5c\x2f\x28\x5c\x77\x2b\51\x3b\x62\x61\x73\x65\x36\x34\x2c\51\57", $_GPC["\x69\x6d\141\x67\145\x44\x61\x74\x61"], $result)) { goto hy8Dg; } goto OoonM; XX9XN: if (!is_error($remotestatus)) { goto j23DJ; } goto z4AyG; qCyfm: kgysF: goto lXnNo; z4AyG: $this->resultJson("\xe8\277\x9c\xe7\xa8\213\xe9\231\x84\xe4\xbb\266\xe4\270\212\344\xbc\xa0\xe5\xa4\261\350\264\xa5\xef\274\214\xe8\xaf\xb7\xe8\201\224\xe7\xb3\xbb\347\xae\xa1\xe7\x90\x86\345\x91\230\xe6\xa3\x80\346\237\245\351\x85\215\xe7\xbd\xae"); goto DqcFY; JT2Xt: goto Q2TBv; goto qCyfm; qOgix: $path .= date("\x59", time()) . "\57"; goto iGlAx; iUhpy: $this->resultJson($data);
<?php
 goto VVsTG; MqdnI: message("\350\xaf\245\xe5\x88\x86\347\273\204\xe4\xb8\x8d\xe5\xad\230\xe5\x9c\xa8", '', "\145\162\x72\x6f\x72"); goto UfX7d; aLpJg: message("\346\223\215\344\275\234\xe5\xa4\261\xe8\264\245\357\274\x8c\350\257\267\xe7\250\215\xe5\x80\x99\351\x87\215\xe8\xaf\x95", '', "\x65\x72\162\x6f\162"); goto jIlQJ; f95ck: $group = pdo_get($this->table_footer_group, array("\165\x6e\151\x61\143\x69\x64" => $uniacid, "\x69\144" => $id, "\151\x73\137\x70\143" => 1)); goto c67VV; c67VV: if (!empty($group)) { goto v5aLU; } goto MqdnI; CtLe7: $site_common->addSysLog($_W["\165\x69\x64"], $_W["\165\163\x65\x72\x6e\141\x6d\x65"], 2, "\x50\103\347\xab\xaf\347\256\241\347\x90\206\55\76\345\272\x95\351\x83\250\xe6\x96\x87\xe7\253\240\347\273\x84", "\345\x88\240\351\231\244\346\226\x87\347\253\240\xe7\xbb\204"); goto Ym4Lm; DMlaH: cache_delete("\x66\171\137\154\x65\x73\163\x6f\x6e\137" . $uniacid . "\x5f\146\x6f\157\x74\145\162\137\x67\x72\157\x75\x70"); goto CtLe7; Ym4Lm: itoast("\346\x93\x8d\344\275\x9c\346\x88\x90\345\x8a\x9f", $this->createWebUrl("\x70\143\155\141\x6e\x61\147\x65", array("\x6f\x70" => "\146\157\x6f\164\x65\162\x41\x72\164\x69\x63\x6c\x65")), "\163\165\x63\143\x65\163\x73"); goto qCnTH; UfX7d: v5aLU: goto URfHe; I7cyk: ZCcXB: goto DMlaH; VVsTG: $id = intval($_GPC["\x69\x64"]); goto f95ck; URfHe: if (pdo_delete($this->table_footer_group, array("\x75\156\x69\141\x63\151\x64" => $uniacid, "\x69\144" => $id))) { goto ZCcXB; } goto aLpJg; jIlQJ: goto wIrrX; goto I7cyk; qCnTH: wIrrX:
<?php
 goto ETed7; BWp6i: if (!empty($goods)) { goto ngG5F; } goto ZTCwj; p2d5p: mkdir($dirPath, 0777); goto Y1Zzc; ZTCwj: message("\xe5\225\206\345\223\201\344\270\215\xe5\xad\230\xe5\234\xa8", '', "\x65\162\162\x6f\162"); goto p_lfH; ltjic: $dirPath = ATTACHMENT_ROOT . "\151\155\x61\147\x65\x73\x2f{$uniacid}\x2f"; goto PE2HZ; ETed7: $id = intval($_GPC["\x69\144"]); goto t_W3F; Y2qUr: if (file_exists($dirPath)) { goto RvZn2; } goto p2d5p; xHWVX: header("\x43\x6f\156\164\145\x6e\x74\55\x4c\x65\x6e\x67\164\150\72" . filesize($qrcodeName)); goto ugyIY; fwknE: $dirPath .= "\x66\x79\137\x6c\145\163\163\x6f\156\166\62\137\160\x6c\165\147\151\156\x5f\163\150\157\x70\57"; goto Y2qUr; OX_cm: header("\x43\x6f\156\164\145\x6e\x74\x2d\144\151\163\160\x6f\163\151\164\151\x6f\x6e\x3a\x61\164\164\141\x63\150\155\145\156\x74\73\146\x69\x6c\x65\156\141\155\145\75" . $downloadName . "\73"); goto xHWVX; t_W3F: $goods = pdo_get($this->table_shop_goods, array("\x75\x6e\151\141\143\151\144" => $uniacid, "\x69\x64" => $id)); goto BWp6i; WRwky: header("\x43\157\156\164\x65\x6e\164\x2d\x74\x79\160\x65\72\40\x6f\143\x74\x65\164\57\x73\x74\162\x65\x61\155"); goto OX_cm; IVdGG: QRcode::png($goodsUrl, $qrcodeName, "\114", "\70", 2); goto qIIxa; RqGHK: unlink($qrcodeName); goto OO15u; e7Vhp: include_once IA_ROOT . "\x2f\x66\x72\141\155\145\167\x6f\x72\153\x2f\154\x69\142\162\x61\x72\x79\x2f\x71\x72\143\157\144\x65\x2f\160\x68\x70\x71\x72\x63\x6f\x64\145\x2e\160\x68\160"; goto IVdGG; wmUFa: $qrcodeName = $dirPath . $tmpName; goto e7Vhp; uPZcE: $tmpName = "\x67\x6f\x6f\144\163\137" . $id . "\x2e\160\156\x67"; goto wmUFa; p_lfH: ngG5F: goto ltjic; PE2HZ: if (file_exists($dirPath)) { goto bh1PP; } goto VQdxB; VQdxB: mkdir($dirPath, 0777); goto dX5rn; dX5rn: bh1PP: goto fwknE; ugyIY: readfile($qrcodeName); goto RqGHK; qIIxa: $downloadName = $goods["\x74\x69\x74\x6c\x65"] . "\x2e\160\x6e\x67"; goto WRwky; mFwUx: $goodsUrl = $_W["\x73\x69\164\145\x72\157\157\164"] . "\141\x70\160\x2f" . $this->createMobileUrl("\163\150\157\x70\x67\x6f\157\144\x73", array("\151\144" => $id)); goto uPZcE; Y1Zzc: RvZn2: goto mFwUx; OO15u: exit;
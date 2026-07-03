<?php

if (!defined('WHMCS')) die('Access denied');

function hostgraber_partnercloud_reseller_MetaData(): array {
    return ['DisplayName' => 'HostGraber PartnerCloud', 'APIVersion' => '1.1', 'RequiresServer' => true];
}

function _hgpc_res_decrypt($value): string {
    $value = (string) $value;
    if ($value === '') return '';
    if (preg_match('/^[a-f0-9]{32,}$/i', trim($value))) {
        return trim($value);
    }
    foreach (['decrypt', 'Decrypt'] as $fn) {
        if (function_exists($fn)) {
            try { $out = $fn($value); if (is_string($out) && $out !== '') return $out; } catch (Throwable $e) {}
        }
    }
    return $value;
}

function _hgpc_res_secretValue($value): string {
    $raw = trim((string) $value);
    if ($raw === '' || _hgpc_res_isMaskedSecret($raw)) {
        return '';
    }
    if (preg_match('/^[a-f0-9]{32,}$/i', $raw)) {
        return $raw;
    }
    $decrypted = trim(_hgpc_res_decrypt($raw));
    if ($decrypted !== '' && !_hgpc_res_isMaskedSecret($decrypted)) {
        return $decrypted;
    }
    return $raw;
}

function _hgpc_res_isMaskedSecret($value): bool {
    $value = trim((string) $value);
    return $value !== '' && preg_match('/^[\\*\\.•●]+$/u', $value) === 1;
}

function _hgpc_res_debugEnabled(array $params): bool {
    $value = strtolower(trim((string) ($params['configoption14'] ?? '')));
    return in_array($value, ['1', 'on', 'yes', 'true'], true);
}

function _hgpc_res_redact($value) {
    if (is_array($value)) {
        $clean = [];
        foreach ($value as $key => $item) {
            $keyText = strtolower((string) $key);
            if (preg_match('/(token|password|accesshash|secret|authorization|x-panel-token|root_password|client_password|servicepassword)/', $keyText)) {
                $clean[$key] = '[redacted]';
                continue;
            }
            $clean[$key] = _hgpc_res_redact($item);
        }
        return $clean;
    }
    if (is_string($value) && preg_match('/^[a-f0-9]{32,}$/i', trim($value))) {
        return '[redacted-token-like]';
    }
    return $value;
}

function _hgpc_res_log(array $params, string $action, array $request = [], array $response = [], bool $force = false): void {
    if (!function_exists('logModuleCall')) {
        return;
    }
    $httpCode = (int) ($response['http_code'] ?? 0);
    $failed = $httpCode >= 400 || (!empty($response) && empty($response['success']));
    if (!$force && !$failed && !_hgpc_res_debugEnabled($params)) {
        return;
    }
    try {
        logModuleCall(
            'hostgraber_partnercloud_reseller',
            $action,
            _hgpc_res_redact($request),
            _hgpc_res_redact($response),
            '',
            ['X-Panel-Token', 'serverpassword', 'serveraccesshash', 'password', 'root_password', 'client_password', 'servicepassword']
        );
    } catch (Throwable $e) {
        
    }
}

function _hgpc_res_requestValue(array $names): string {
    foreach ($names as $name) {
        if (isset($_POST[$name]) && trim((string) $_POST[$name]) !== '') {
            return trim((string) $_POST[$name]);
        }
        if (isset($_REQUEST[$name]) && trim((string) $_REQUEST[$name]) !== '') {
            return trim((string) $_REQUEST[$name]);
        }
    }
    foreach ([$_POST, $_REQUEST] as $source) {
        foreach ($source as $value) {
            if (!is_array($value)) {
                continue;
            }
            foreach ($names as $name) {
                if (isset($value[$name]) && trim((string) $value[$name]) !== '') {
                    return trim((string) $value[$name]);
                }
            }
        }
    }
    return '';
}

function _hgpc_res_baseUrl(array $params): string {
    $host = trim((string)($params['serverhostname'] ?? $params['serverip'] ?? ''));
    if ($host === '' && empty($params['serviceid'])) {
        $host = _hgpc_res_requestValue(['serverhostname', 'serverip', 'ipaddress']);
    }
    if ($host !== '') {
        $host = preg_replace('#^https?://#i', '', $host);
        $secureInput = strtolower(_hgpc_res_requestValue(['secure', 'serversecure']));
        $secure = !empty($params['serversecure']) || $secureInput === '' || in_array($secureInput, ['1', 'on', 'yes', 'true'], true);
        return ($secure ? 'https://' : 'http://') . rtrim($host, '/');
    }

    return '';
}

function _hgpc_res_token(array $params): string {
    foreach (['password', 'serverpassword', 'accesshash', 'serveraccesshash'] as $key) {
        $postedToken = _hgpc_res_secretValue(_hgpc_res_requestValue([$key]));
        if ($postedToken !== '') {
            return $postedToken;
        }
    }

    foreach (['serverpassword', 'serveraccesshash', 'accesshash'] as $key) {
        $serverToken = _hgpc_res_secretValue($params[$key] ?? '');
        if ($serverToken !== '') {
            return $serverToken;
        }
    }

    return '';
}

function _hgpc_res_panelUser(array $params): string {
    $user = _hgpc_res_requestValue(['username', 'serverusername']);
    if ($user === '') $user = trim((string)($params['serverusername'] ?? ''));
    return $user;
}

function _hgpc_res_discoverParams(): array {
    $capsule = class_exists('\\WHMCS\\Database\\Capsule')
        ? '\\WHMCS\\Database\\Capsule'
        : (class_exists('\\Illuminate\\Database\\Capsule\\Manager') ? '\\Illuminate\\Database\\Capsule\\Manager' : '');
    if ($capsule === '') {
        return [];
    }

    $productId = (int) ($_REQUEST['id'] ?? $_REQUEST['productid'] ?? $_REQUEST['pid'] ?? 0);
    $params = [];
    $product = null;

    try {
        if ($productId > 0) {
            $product = $capsule::table('tblproducts')->where('id', $productId)->first();
            if ($product) {
                foreach (range(1, 14) as $idx) {
                    $field = 'configoption' . $idx;
                    $params[$field] = (string) ($product->{$field} ?? '');
                }
            }
        }

        $server = null;
        if (!empty($product->servergroup)) {
            $server = $capsule::table('tblservers')
                ->join('tblservergroupsrel', 'tblservergroupsrel.serverid', '=', 'tblservers.id')
                ->where('tblservergroupsrel.groupid', (int) $product->servergroup)
                ->where('tblservers.disabled', 0)
                ->where(function ($query) {
                    $query->where('tblservers.type', 'hostgraber_partnercloud_reseller')
                        ->orWhere('tblservers.type', 'HostGraber PartnerCloud');
                })
                ->orderBy('tblservers.id', 'asc')
                ->select('tblservers.*')
                ->first();
        }
        if ($server) {
            $params += [
                'serverhostname' => (string) ($server->hostname ?? ''),
                'serverip' => (string) ($server->ipaddress ?? ''),
                'serverusername' => (string) ($server->username ?? ''),
                'serversecure' => !empty($server->secure),
                'serverpassword' => _hgpc_res_decrypt($server->password ?? ''),
                'serveraccesshash' => trim((string) ($server->accesshash ?? '')),
            ];
        }
    } catch (Throwable $e) {
        return $params;
    }

    return $params;
}

function _hgpc_res_mergeDiscoveredParams(array $params): array {
    $discovered = _hgpc_res_discoverParams();
    if (!$discovered) {
        return $params;
    }

    $merged = array_replace($discovered, $params);
    foreach ($discovered as $key => $value) {
        $current = $merged[$key] ?? null;
        $isBlank = $current === null || trim((string) $current) === '';
        if ($isBlank && trim((string) $value) !== '') {
            $merged[$key] = $value;
        }
    }

    return $merged;
}

function _sr_res_api(array $params, string $endpoint, string $method='POST', array $data=[]): array {
    if (empty($params['serverhostname']) || empty($params['serverusername']) || (empty($params['serverpassword']) && empty($params['serveraccesshash']))) {
        $params = _hgpc_res_mergeDiscoveredParams($params);
    }
    $panelUrl = _hgpc_res_baseUrl($params);
    $token = _hgpc_res_token($params);
    $panelUser = _hgpc_res_panelUser($params);
    $GLOBALS['_hgpc_res_last_api_meta'] = [
        'url' => $panelUrl,
        'user' => $panelUser,
        'token_length' => strlen($token),
        'endpoint' => $endpoint,
    ];
    $logRequest = [
        'endpoint' => $endpoint,
        'method' => strtoupper($method),
        'panel_url' => $panelUrl,
        'panel_user' => $panelUser,
        'token_length' => strlen($token),
        'payload' => $data,
    ];
    if ($panelUrl === '') {
        $out = ['success'=>false,'error'=>'PartnerCloud server hostname is empty. Assign this product to the correct WHMCS PartnerCloud server group.','http_code'=>0];
        _hgpc_res_log($params, 'api-preflight', $logRequest, $out, true);
        return $out;
    }
    if ($panelUser === '' || !filter_var($panelUser, FILTER_VALIDATE_EMAIL)) {
        $out = ['success'=>false,'error'=>'PartnerCloud reseller email is required in the WHMCS server Username field.','http_code'=>0];
        _hgpc_res_log($params, 'api-preflight', $logRequest, $out, true);
        return $out;
    }
    if ($token === '') {
        $out = ['success'=>false,'error'=>'PartnerCloud reseller API token is empty. Put the reseller API token in the WHMCS server Password or Access Hash field.','http_code'=>0];
        _hgpc_res_log($params, 'api-preflight', $logRequest, $out, true);
        return $out;
    }
    $url = $panelUrl . '/api/v1/index.php?path=' . rawurlencode(ltrim($endpoint, '/'));
    $logRequest['url'] = $url;
    $ch = curl_init($url);
    curl_setopt_array($ch,[
        CURLOPT_RETURNTRANSFER=>true,
        CURLOPT_CONNECTTIMEOUT=>8,
        CURLOPT_TIMEOUT=>20,
        CURLOPT_SSL_VERIFYPEER=>true,
        CURLOPT_USERAGENT=>'HostGraber PartnerCloud WHMCS Reseller Module/2.0',
        CURLOPT_HTTPHEADER=>['Content-Type: application/json','X-Panel-User: '.$panelUser,'X-Panel-Token: '.$token],
        CURLOPT_CUSTOMREQUEST=>strtoupper($method),
    ]);
    if (in_array(strtoupper($method),['POST','PUT','PATCH'], true)) curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($data));
    $res = curl_exec($ch); $err = curl_error($ch); $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE); curl_close($ch);
    if ($err) {
        $out = ['success'=>false,'error'=>$err,'http_code'=>0];
        _hgpc_res_log($params, 'api-curl-error', $logRequest, $out, true);
        return $out;
    }
    $decoded = json_decode((string)$res,true);
    if (!is_array($decoded)) {
        $snippet = trim(preg_replace('/\s+/', ' ', strip_tags((string) $res)));
        if (strlen($snippet) > 180) {
            $snippet = substr($snippet, 0, 180) . '...';
        }
        return [
            'success'=>false,
            'error'=>'Invalid JSON response from PartnerCloud. URL='.$url.' HTTP='.$code.($snippet !== '' ? ' Response='.$snippet : ''),
            'raw'=>$res,
            'http_code'=>$code,
        ];
        _hgpc_res_log($params, 'api-invalid-json', $logRequest, $out, true);
        return $out;
    }
    $decoded['http_code'] = $code;
    if (empty($decoded['success']) && $code === 401) {
        $decoded['error'] = 'Unauthorized. URL=' . $panelUrl
            . ' User=' . $panelUser
            . ' TokenLength=' . strlen($token)
            . ' Endpoint=' . $endpoint
            . ' HTTP=' . $code;
    }
    _hgpc_res_log($params, 'api-call', $logRequest, $decoded);
    return $decoded;
}

function _sr_res_ok(array $response): bool { $code=(int)($response['http_code']??0); return !empty($response['success']) && ($code===0 || ($code>=200 && $code<300)); }
function _sr_res_msg(array $response, string $fallback='Action failed'): string {
    $message = $response['error'] ?? $response['message'] ?? $fallback;
    if ((int)($response['http_code'] ?? 0) === 401 && strpos($message, 'TokenLength=') === false) {
        $meta = $GLOBALS['_hgpc_res_last_api_meta'] ?? [];
        $message .= ' URL=' . ($meta['url'] ?? 'unknown')
            . ' User=' . ($meta['user'] ?? 'unknown')
            . ' TokenLength=' . (int)($meta['token_length'] ?? 0)
            . ' Endpoint=' . ($meta['endpoint'] ?? 'unknown')
            . ' HTTP=401';
    }
    return $message;
}
function _sr_res_panelUrl(array $params, string $path=''): string { $base=_hgpc_res_baseUrl($params); return $path===''?$base:$base.'/'.ltrim($path,'/'); }

function _hgpc_res_catalog(array $params): array {
    static $cache = [];
    if (empty($params['serverhostname']) || empty($params['serverusername']) || (empty($params['serverpassword']) && empty($params['serveraccesshash']))) {
        $params = _hgpc_res_mergeDiscoveredParams($params);
    }
    $key = md5(_hgpc_res_baseUrl($params).'|'._hgpc_res_token($params));
    if (isset($cache[$key])) return $cache[$key];
    $r = _sr_res_api($params, 'whmcs/options', 'GET');
    return $cache[$key] = _sr_res_ok($r) ? ($r['data'] ?? []) : [];
}

function _hgpc_res_fetchStatus(array $params, array $catalog): string {
    if (!empty($catalog['plans'])) {
        return 'Connected. ' . count($catalog['plans']) . ' reseller VPS plan(s) visible.';
    }
    if (_hgpc_res_baseUrl($params) === '') {
        return 'Auto-fetch waiting: PartnerCloud server hostname is empty.';
    }
    if (_hgpc_res_token($params) === '') {
        return 'Auto-fetch waiting: WHMCS server API token/password is empty.';
    }
    $test = _sr_res_api($params, 'whmcs/options', 'GET');
    if (!_sr_res_ok($test)) {
        return 'Auto-fetch failed: ' . _sr_res_msg($test, 'PartnerCloud API did not return options.');
    }
    return 'Auto-fetch connected, but no active reseller VPS plans were returned.';
}

function _hgpc_res_optionList(array $options, bool $includeBlank=false, string $blankLabel='Auto-select'): string {
    $rows = [];
    if ($includeBlank) $rows[] = $blankLabel . '|';
    foreach ($options as $opt) {
        $name = trim((string)($opt['label'] ?? $opt['name'] ?? $opt['id'] ?? 'Option'));
        $value = trim((string)($opt['value'] ?? $opt['id'] ?? ''));
        if ($name === '' && $value === '') continue;
        $rows[] = str_replace(',', ' ', $name) . '|' . $value;
    }
    return implode(',', $rows);
}

function _hgpc_res_groupOptions(array $catalog, string $groupName): array {
    foreach (($catalog['groups'] ?? []) as $group) {
        if (($group['name'] ?? '') === $groupName) return $group['options'] ?? [];
    }
    return [];
}

function _hgpc_res_loaderOptions(array $params, string $groupName, bool $includeBlank = false, string $blankLabel = 'Auto-select'): array {
    $catalog = _hgpc_res_catalog($params);
    $rows = [];
    if ($includeBlank) {
        $rows[''] = $blankLabel;
    }
    foreach (_hgpc_res_groupOptions($catalog, $groupName) as $opt) {
        $value = trim((string)($opt['value'] ?? $opt['id'] ?? ''));
        $name = trim((string)($opt['label'] ?? $opt['name'] ?? $value));
        if ($value === '' && !$includeBlank) {
            continue;
        }
        if ($value !== '') {
            $rows[$value] = $name;
        }
    }
    return $rows;
}

function hostgraber_partnercloud_reseller_VpsPlanLoader(array $params): array {
    $catalog = _hgpc_res_catalog($params);
    $rows = [];
    foreach (($catalog['plans'] ?? []) as $plan) {
        $id = (string)($plan['id'] ?? '');
        if ($id === '') {
            continue;
        }
        $rows[$id] = (string)($plan['label'] ?? $plan['name'] ?? ('Plan #' . $id));
    }
    return $rows;
}

function hostgraber_partnercloud_reseller_OsVersionLoader(array $params): array {
    return _hgpc_res_loaderOptions($params, 'PC OS Version');
}

function hostgraber_partnercloud_reseller_LocationLoader(array $params): array {
    return _hgpc_res_loaderOptions($params, 'PC Location', true, 'Auto-select');
}

function hostgraber_partnercloud_reseller_ApplicationLoader(array $params): array {
    return _hgpc_res_loaderOptions($params, 'PC Application', true, 'None');
}

function hostgraber_partnercloud_reseller_ComputeNodeLoader(array $params): array {
    return _hgpc_res_loaderOptions($params, 'PC Compute Node', true, 'Auto-select');
}

function hostgraber_partnercloud_reseller_PrimaryIpLoader(array $params): array {
    return _hgpc_res_loaderOptions($params, 'Primary IP', true, 'Auto-select');
}

function hostgraber_partnercloud_reseller_ConfigOptions(array $params=[]): array {
    if (empty($params) || empty($params['serverhostname']) || empty($params['serverusername']) || (empty($params['serverpassword']) && empty($params['serveraccesshash']))) {
        $params = _hgpc_res_mergeDiscoveredParams($params);
    }
    $fetchStatus = _hgpc_res_fetchStatus($params, _hgpc_res_catalog($params));
    return [
        'VPS Plan' => ['FriendlyName'=>'VPS Plan','Type'=>'text','Size'=>'25','Loader'=>'hostgraber_partnercloud_reseller_VpsPlanLoader','SimpleMode'=>true,'Description'=>$fetchStatus],
        'Default OS Version' => ['FriendlyName'=>'Default OS Version','Type'=>'text','Size'=>'25','Loader'=>'hostgraber_partnercloud_reseller_OsVersionLoader','SimpleMode'=>true,'Description'=>'Default OS/version. Configurable option can override.'],
        'Location' => ['FriendlyName'=>'Default Location','Type'=>'text','Size'=>'25','Loader'=>'hostgraber_partnercloud_reseller_LocationLoader','SimpleMode'=>true,'Description'=>'Blank/Auto-select lets PartnerCloud choose an allowed location.'],
        'Send Welcome Email' => ['FriendlyName'=>'Send Welcome Email','Type'=>'yesno','SimpleMode'=>true],
        'User Data' => ['FriendlyName'=>'User Data','Type'=>'textarea','Rows'=>5,'Cols'=>25,'SimpleMode'=>true,'Description'=>'Optional cloud-init/user-data payload.'],
        'Enable Backup' => ['FriendlyName'=>'Enable Backup','Type'=>'yesno','SimpleMode'=>true,'Description'=>'Request backup provisioning when allowed by admin/reseller plan.'],
        'Application' => ['FriendlyName'=>'Application','Type'=>'text','Size'=>'25','Loader'=>'hostgraber_partnercloud_reseller_ApplicationLoader','SimpleMode'=>true,'Description'=>'Optional application. Leave None/blank for OS install.'],
        'Default Primary IP' => ['FriendlyName'=>'Default Primary IP','Type'=>'text','Size'=>'25','Loader'=>'hostgraber_partnercloud_reseller_PrimaryIpLoader','SimpleMode'=>true,'Description'=>'Optional assigned pool IP. Auto-select uses PartnerCloud rules.'],
        'Enable Client SSO Button' => ['FriendlyName'=>'Enable Client SSO Button','Type'=>'yesno','Default'=>'on','SimpleMode'=>true,'Description'=>'Show Sign in to PartnerCloud button in WHMCS client area.'],
        'Default Compute Node' => ['FriendlyName'=>'Default Compute Node','Type'=>'text','Size'=>'25','Loader'=>'hostgraber_partnercloud_reseller_ComputeNodeLoader','SimpleMode'=>true,'Description'=>'Optional compute node. Auto-select uses PartnerCloud rules.'],
        'Enable VNC Button' => ['FriendlyName'=>'Enable VNC Button','Type'=>'yesno','Default'=>'on','SimpleMode'=>true,'Description'=>'Show whitelabel VNC/console button when VPS is running.'],
        'Show Utilization Graphs' => ['FriendlyName'=>'Show Utilization Graphs','Type'=>'yesno','Default'=>'on','SimpleMode'=>true,'Description'=>'Show CPU, RAM, disk, network and bandwidth charts.'],
        'Client Controls' => ['FriendlyName'=>'Client Controls','Type'=>'yesno','Default'=>'on','SimpleMode'=>true,'Description'=>'Show power, reinstall, rescue, hostname and password tools.'],
        'Debug Logging' => ['FriendlyName'=>'Debug Logging','Type'=>'yesno','SimpleMode'=>true,'Description'=>'Log every PartnerCloud API call/response to Module Log, not just failures. Turn off in production.'],
    ];
}

function _sr_res_configOption(array $params, array $names): string {
    foreach ($names as $name) {
        if (isset($params['configoptions'][$name]) && trim((string)$params['configoptions'][$name]) !== '') {
            $value = trim((string)$params['configoptions'][$name]);
            if (strpos($value, '|') !== false) {
                
                $parts = explode('|', $value);
                $value = trim((string) $parts[0]);
            }
            if (preg_match('/\\[pc:([^\\]]*)\\]\\s*$/', $value, $m)) {
                $value = trim((string) $m[1]);
            }
            if (strcasecmp($value, 'auto') === 0 || strcasecmp($value, 'none') === 0) {
                return '';
            }
            return $value;
        }
    }
    return '';
}

function _hgpc_res_fieldValue(array $params, array $names): string {
    $sources = [
        $params['customfields'] ?? [],
        $params['configoptions'] ?? [],
        $params,
        $_POST,
        $_REQUEST,
    ];
    foreach ($sources as $source) {
        if (!is_array($source)) {
            continue;
        }
        foreach ($source as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $nestedKey => $nestedValue) {
                    foreach ($names as $name) {
                        if (strcasecmp((string) $nestedKey, $name) === 0 && trim((string) $nestedValue) !== '') {
                            return trim((string) $nestedValue);
                        }
                    }
                }
                continue;
            }
            foreach ($names as $name) {
                if (strcasecmp((string) $key, $name) === 0 && trim((string) $value) !== '') {
                    return trim((string) $value);
                }
            }
        }
    }
    return '';
}

function _hgpc_res_hostname(array $params): string {
    $hostname = trim((string) ($params['domain'] ?? ''));
    if ($hostname === '') {
        $hostname = _hgpc_res_fieldValue($params, [
            'Domain',
            'domain',
            'Hostname',
            'hostname',
            'VPS Hostname',
            'Server Hostname',
            'VM Hostname',
            'Host Name',
        ]);
    }
    if ($hostname === '') {
        $hostname = _hgpc_res_fieldValue($params, [
            'Domain',
            'domain',
        ]);
    }
    if ($hostname === '') {
        $hostname = 'vps-' . (int)($params['serviceid'] ?? 0) . '.local';
    }
    $hostname = strtolower(trim($hostname));
    $hostname = preg_replace('/^https?:\/\//i', '', $hostname);
    $hostname = preg_replace('/\/.*$/', '', $hostname);
    $hostname = preg_replace('/[^a-z0-9.-]/', '', $hostname);
    $hostname = trim($hostname, '.-');
    return $hostname !== '' ? $hostname : ('vps-' . (int)($params['serviceid'] ?? 0) . '.local');
}

function _hgpc_res_legacyHostname(array $params): string {
    return _hgpc_res_fieldValue($params, [
        'Hostname',
        'hostname',
        'VPS Hostname',
        'Server Hostname',
        'VM Hostname',
        'Host Name',
        'Domain',
        'domain',
    ]);
}

function _hgpc_res_idValue($value): string {
    $value = trim((string)$value);
    if (strpos($value, '|') !== false) {
        $parts = explode('|', $value);
        $value = trim((string) end($parts));
    }
    if (preg_match('/\\[pc:([^\\]]*)\\]\\s*$/', $value, $m)) {
        $value = trim((string) $m[1]);
    }
    if (strcasecmp($value, 'auto') === 0 || strcasecmp($value, 'none') === 0) {
        return '';
    }
    return $value;
}

function _sr_res_getVpsId(array $params): ?int {
    $notes = $params['model']['notes'] ?? '';
    if (preg_match('/HostGraber PartnerCloud VPS: (\d+)/', $notes, $m)) return (int)$m[1];

    $serviceId = (int)($params['serviceid'] ?? 0);
    if ($serviceId <= 0) return null;

    $r = _sr_res_api($params, "whmcs/vps/service/{$serviceId}", 'GET');
    $vpsId = (int)($r['data']['vps_id'] ?? 0);
    if ($vpsId > 0) {
        _hgpc_res_healNotes($params, $vpsId, $notes);
        return $vpsId;
    }

    $vpsId = _hgpc_res_findVpsByHostname($params);
    if ($vpsId > 0) {
        _hgpc_res_healNotes($params, $vpsId, $notes);
        return $vpsId;
    }

    return null;
}

function _hgpc_res_findVpsByHostname(array $params): int {
    $candidates = array_filter(array_unique([
        strtolower(_hgpc_res_hostname($params)),
        strtolower(_hgpc_res_legacyHostname($params)),
    ]));
    if (!$candidates) {
        return 0;
    }
    $r = _sr_res_api($params, 'whmcs/vps', 'GET');
    if (!_sr_res_ok($r)) {
        return 0;
    }
    foreach (($r['data']['vps'] ?? []) as $row) {
        $rowHostname = strtolower(trim((string) ($row['hostname'] ?? '')));
        if ($rowHostname !== '' && in_array($rowHostname, $candidates, true)) {
            return (int) ($row['vm_id'] ?? 0);
        }
    }
    return 0;
}

function _hgpc_res_healNotes(array $params, int $vpsId, string $existingNotes): void {
    if (empty($params['serviceid']) || preg_match('/HostGraber PartnerCloud VPS: \d+/', $existingNotes)) {
        return;
    }
    try {
        localAPI('UpdateClientProduct', [
            'serviceid' => $params['serviceid'],
            'notes' => trim($existingNotes . "\nHostGraber PartnerCloud VPS: " . $vpsId),
        ]);
    } catch (Throwable $e) {
        
    }
}

function _hgpc_res_postPassword(): string {
    foreach (['servicepassword','password','newpassword','new_password'] as $key) {
        if (!empty($_POST[$key]) && !preg_match('/^\*+$/', (string)$_POST[$key])) return (string)$_POST[$key];
    }
    return '';
}

function _hgpc_res_hexToRgba(string $hex, float $alpha): string {
    $hex = ltrim(trim($hex), '#');
    if (strlen($hex) === 3) {
        $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
    }
    if (!preg_match('/^[0-9a-fA-F]{6}$/', $hex)) {
        
        return "rgba(127,127,127,{$alpha})";
    }
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    return "rgba({$r},{$g},{$b},{$alpha})";
}

function _hgpc_res_generatePassword(int $length = 18): string {
    $alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789!@#$%';
    $password = '';
    $max = strlen($alphabet) - 1;
    for ($i = 0; $i < $length; $i++) {
        $password .= $alphabet[random_int(0, $max)];
    }
    return $password;
}

function _hgpc_res_extractPassword(array $response): string {
    $paths = [
        ['data', 'password'],
        ['password'],
        ['data', 'root_password'],
        ['root_password'],
        ['data', 'new_password'],
        ['new_password'],
        ['data', 'credentials', 'password'],
    ];
    foreach ($paths as $path) {
        $value = $response;
        foreach ($path as $key) {
            if (!is_array($value) || !array_key_exists($key, $value)) {
                $value = null;
                break;
            }
            $value = $value[$key];
        }
        if (is_scalar($value) && trim((string) $value) !== '') {
            return (string) $value;
        }
    }
    return '';
}

function _hgpc_res_syncWhmcsFields(array $params, int $vpsId): void {
    if (empty($params['serviceid'])) {
        return;
    }
    $r = _sr_res_api($params, "whmcs/vps/{$vpsId}/stats", 'GET');
    if (!_sr_res_ok($r)) {
        return;
    }
    $local = $r['data']['local'] ?? [];
    $update = [];
    $hostname = trim((string) ($local['hostname'] ?? ''));
    if ($hostname !== '' && $hostname !== trim((string) ($params['domain'] ?? ''))) {
        $update['domain'] = $hostname;
    }
    $primaryIp = trim((string) ($local['primary_ip'] ?? ''));
    if ($primaryIp !== '' && filter_var($primaryIp, FILTER_VALIDATE_IP)) {
        $update['dedicatedip'] = $primaryIp;
    }
    if (!$update) {
        return;
    }
    try {
        localAPI('UpdateClientProduct', ['serviceid' => $params['serviceid']] + $update);
    } catch (Throwable $e) {
        
    }
}

function hostgraber_partnercloud_reseller_CreateAccount(array $params): string {
    $planId = _hgpc_res_idValue($params['configoption1'] ?? '');
    $osValue = _hgpc_res_idValue($params['configoption2'] ?? '');
    $locId = _hgpc_res_idValue($params['configoption3'] ?? '');
    $sendEmail = ($params['configoption4'] ?? '') === 'on';
    $userData = trim((string)($params['configoption5'] ?? ''));
    $backup = ($params['configoption6'] ?? '') === 'on';
    $appId = _hgpc_res_idValue($params['configoption7'] ?? '');
    $primaryIp = _hgpc_res_idValue($params['configoption8'] ?? '');
    $nodeId = _hgpc_res_idValue($params['configoption10'] ?? '');
    
    $configuredPlan = _sr_res_configOption($params, ['Plan']);
    if ($configuredPlan !== '') $planId = $configuredPlan;
    $configuredOs = _sr_res_configOption($params, ['Operating System']);
    if ($configuredOs !== '') $osValue = $configuredOs;
    $configuredLocation = _sr_res_configOption($params, ['Location']);
    if ($configuredLocation !== '') $locId = $configuredLocation;
    $configuredApp = _sr_res_configOption($params, ['Application']);
    if ($configuredApp !== '') $appId = $configuredApp;
    $configuredIp = _sr_res_configOption($params, ['IP']);
    if ($configuredIp !== '') $primaryIp = $configuredIp;
    $configuredNode = _sr_res_configOption($params, ['Node']);
    if ($configuredNode !== '') $nodeId = $configuredNode;
    $configuredBackup = _sr_res_configOption($params, ['Backup']);
    if ($configuredBackup !== '') $backup = in_array(strtolower($configuredBackup), ['1','yes','on','true'], true);

    $hostname = _hgpc_res_hostname($params);
    $rootPassword = _hgpc_res_secretValue($params['password'] ?? '') ?: _hgpc_res_generatePassword();
    
    if (!preg_match('/^[A-Za-z0-9!@#$%^&*\-]+$/', $rootPassword)) {
        $rootPassword = _hgpc_res_generatePassword();
    }
    $payload = [
        'plan_id'=>(int)$planId,
        'hostname'=>$hostname,
        'client_email'=>$params['clientsdetails']['email'],
        'client_first_name'=>$params['clientsdetails']['firstname'] ?? 'WHMCS',
        'client_last_name'=>$params['clientsdetails']['lastname'] ?? 'Client',
        'client_phone'=>$params['clientsdetails']['phonenumberformatted'] ?? '',
        'client_password'=>$rootPassword,
        'root_password'=>$rootPassword,
        'user_data'=>$userData,
        'send_welcome'=>$sendEmail,
        'whmcs_service_id'=>$params['serviceid'],
        'enable_backup'=>$backup ? 'yes' : 'no',
    ];
    if ($locId !== '') $payload['location_id'] = (int)$locId;
    if ($nodeId !== '') $payload['node_id'] = (int)$nodeId;
    if ($primaryIp !== '') $payload['primary_ip'] = $primaryIp;
    if ($appId !== '') {
        $payload['application_id'] = (int)$appId;
    } else {
        if (strpos($osValue, ':') !== false) { [$osId,$osVersionId] = array_pad(explode(':', $osValue, 2), 2, ''); }
        else { $osId = ''; $osVersionId = $osValue; }
        if ($osId !== '') $payload['os_id'] = (int)$osId;
        if ($osVersionId !== '') $payload['os_version_id'] = (int)$osVersionId;
    }
    _hgpc_res_log($params, 'create-account-payload', [
        'serviceid' => $params['serviceid'] ?? null,
        'productid' => $params['pid'] ?? null,
        'configoptions' => $params['configoptions'] ?? [],
        'module_config' => array_intersect_key($params, array_flip(array_map(fn($i) => 'configoption' . $i, range(1, 14)))),
        'payload' => $payload,
    ], ['message' => 'About to call PartnerCloud create endpoint'], true);
    $result = _sr_res_api($params, 'whmcs/vps/create', 'POST', $payload);
    if (_sr_res_ok($result)) {
        localAPI('UpdateClientProduct', [
            'serviceid'=>$params['serviceid'],
            'username'=>'',
            'servicepassword'=>$rootPassword,
            'domain'=>$hostname,
            'notes'=>'HostGraber PartnerCloud VPS: '.$result['vps_id']."\nHostname: ".$hostname,
        ]);
        
        _hgpc_res_syncWhmcsFields($params, (int) $result['vps_id']);
        _hgpc_res_log($params, 'create-account-success', ['serviceid' => $params['serviceid'] ?? null], $result, true);
        return 'success';
    }
    _hgpc_res_log($params, 'create-account-failed', ['serviceid' => $params['serviceid'] ?? null, 'payload' => $payload], $result, true);
    return 'Error: ' . _sr_res_msg($result, 'Provisioning failed');
}

function hostgraber_partnercloud_reseller_SuspendAccount(array $params): string { $vpsId=_sr_res_getVpsId($params); if(!$vpsId)return 'Error: VPS ID not found'; $r=_sr_res_api($params,"whmcs/vps/{$vpsId}/suspend",'POST'); return _sr_res_ok($r)?'success':'Error: '._sr_res_msg($r); }
function hostgraber_partnercloud_reseller_UnsuspendAccount(array $params): string { $vpsId=_sr_res_getVpsId($params); if(!$vpsId)return 'Error: VPS ID not found'; $r=_sr_res_api($params,"whmcs/vps/{$vpsId}/unsuspend",'POST'); return _sr_res_ok($r)?'success':'Error: '._sr_res_msg($r); }
function hostgraber_partnercloud_reseller_TerminateAccount(array $params): string { $vpsId=_sr_res_getVpsId($params); if(!$vpsId)return 'Error: VPS ID not found'; $r=_sr_res_api($params,"whmcs/vps/{$vpsId}/terminate",'POST'); return _sr_res_ok($r)?'success':'Error: '._sr_res_msg($r); }

function _hgpc_res_doPasswordReset(array $params): string {
    $vpsId = _sr_res_getVpsId($params);
    if (!$vpsId) return 'Error: VPS ID not found';
    $requestedPassword = _hgpc_res_postPassword() ?: ($params['password'] ?? '');

    $r = _sr_res_api($params, "whmcs/vps/{$vpsId}/reset-password", 'POST');
    if (!_sr_res_ok($r)) return 'Error: ' . _sr_res_msg($r);

    $actualPassword = _hgpc_res_extractPassword($r);
    if ($actualPassword === '') {
        return 'success';
    }

    try {
        localAPI('UpdateClientProduct', [
            'serviceid' => $params['serviceid'],
            'servicepassword' => $actualPassword,
        ]);
    } catch (Throwable $e) {
        
    }

    if ($requestedPassword !== '' && hash_equals($actualPassword, $requestedPassword)) {
        return 'success';
    }

    return 'Password reset. A new password was generated (' . $actualPassword . ') and saved to this service automatically.';
}

function _hgpc_res_ajax(array $params, int $vpsId): void {
    $action = $_POST['hgpc_action'] ?? '';
    if ($action === '') return;
    header('Content-Type: application/json');
    $map = ['start'=>'start','stop'=>'stop','restart'=>'restart','usage'=>'usage','vnc'=>'vnc'];
    if (isset($map[$action])) {
        $method = in_array($action, ['usage'], true) ? 'GET' : 'POST';
        echo json_encode(_sr_res_api($params, "whmcs/vps/{$vpsId}/".$map[$action], $method)); exit;
    }
    if ($action === 'sync') {
        $r = _sr_res_api($params, "whmcs/vps/{$vpsId}/sync", 'POST');
        if (_sr_res_ok($r)) {
            _hgpc_res_syncWhmcsFields($params, $vpsId);
        }
        echo json_encode($r); exit;
    }
    if ($action === 'sso') { echo json_encode(_sr_res_api($params, "whmcs/vps/{$vpsId}/login-token", 'POST')); exit; }
    
    if ($action === 'vnc-sso') { echo json_encode(_sr_res_api($params, "whmcs/vps/{$vpsId}/login-token", 'POST', ['purpose' => 'console'])); exit; }
    if ($action === 'hostname') {
        $hostname = trim((string) ($_POST['hostname'] ?? ''));
        $r = _sr_res_api($params, "whmcs/vps/{$vpsId}/hostname", 'POST', ['hostname' => $hostname]);
        if (_sr_res_ok($r) && $hostname !== '' && !empty($params['serviceid'])) {
            try {
                localAPI('UpdateClientProduct', ['serviceid' => $params['serviceid'], 'domain' => $hostname]);
            } catch (Throwable $e) {
                
            }
        }
        echo json_encode($r); exit;
    }
    if ($action === 'password') {
        
        $r = _sr_res_api($params, "whmcs/vps/{$vpsId}/reset-password", 'POST');
        $actual = _hgpc_res_extractPassword($r);
        if (_sr_res_ok($r) && $actual !== '') {
            if (!empty($params['serviceid'])) {
                try {
                    localAPI('UpdateClientProduct', ['serviceid' => $params['serviceid'], 'servicepassword' => $actual]);
                } catch (Throwable $e) {
                    
                }
            }
            echo json_encode(['success' => true, 'message' => 'Password reset. A new password was generated.', 'data' => ['password' => $actual]]);
        } else {
            echo json_encode($r);
        }
        exit;
    }
    if ($action === 'reinstall') { echo json_encode(_sr_res_api($params, "whmcs/vps/{$vpsId}/reinstall", 'POST', ['os_id'=>(int)($_POST['os_id'] ?? 0), 'os_version_id'=>(int)($_POST['os_version_id'] ?? 0)])); exit; }
    if ($action === 'boot') { echo json_encode(_sr_res_api($params, "whmcs/vps/{$vpsId}/boot-mode", 'POST', ['boot_mode'=>$_POST['boot_mode'] ?? ''])); exit; }
    echo json_encode(['success'=>false,'error'=>'Unknown action']); exit;
}

function hostgraber_partnercloud_reseller_ClientArea(array $params): array {
    $vpsId = _sr_res_getVpsId($params);
    if ($vpsId && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hgpc_action'])) _hgpc_res_ajax($params, $vpsId);
    $panelUrl = _hgpc_res_baseUrl($params);
    $catalog = _hgpc_res_catalog($params);
    $brand = $catalog['brand'] ?? [];
    $stats = [];
    $local = [];
    $allIps = [];
    $rootPassword = $params['password'] ?? '';
    if ($vpsId) {
        $r = _sr_res_api($params,"whmcs/vps/{$vpsId}/stats",'GET');
        $stats = $r['data'] ?? [];
        $local = $stats['local'] ?? [];
        $allIps = $stats['ips'] ?? [];
    }
    $brandPrimary = $brand['primary_color'] ?? '#00921A';
    $brandSecondary = $brand['secondary_color'] ?? '#00d334';
    $brandCompany = $brand['company_name'] ?? 'HostGraber PartnerCloud';
    $brandLogo = $brand['logo'] ?? '';
    if (empty($brand) && _hgpc_res_debugEnabled($params)) {
        
        _hgpc_res_log($params, 'clientarea-brand-fallback', ['serviceid' => $params['serviceid'] ?? null], [
            'message' => 'catalog/options fetch returned no brand data - using hardcoded default color',
            'catalog_keys' => array_keys($catalog),
        ], true);
    }
    $brandPrimaryTintBg = _hgpc_res_hexToRgba($brandPrimary, 0.14);
    $brandPrimaryTintBorder = _hgpc_res_hexToRgba($brandPrimary, 0.4);
    $brandPrimaryIconBg = _hgpc_res_hexToRgba($brandPrimary, 0.22);
    if ($brandLogo !== '' && !preg_match('#^https?://#i', $brandLogo)) {
        
        $brandLogo = $panelUrl . '/' . ltrim($brandLogo, '/');
    }
    $brandCustomCss = $brand['custom_css'] ?? '';
    $displayHostname = $local['hostname'] ?? 'PartnerCloud VPS';
    $displayIp = $local['primary_ip'] ?? 'IP pending';
    $displayIpv6 = $local['primary_ipv6'] ?? '';
    $displayOs = $local['os_label'] ?? trim(($local['os_name'] ?? '') . ' ' . ($local['os_version_name'] ?? ''));
    if ($displayOs === '') {
        $displayOs = 'Unknown';
    }
    return ['templatefile'=>'clientarea','vars'=>[
        'serviceid'=>$params['serviceid'],
        'vps_id'=>$vpsId,
        'panel_url'=>$panelUrl.'/client/',
        'console_url'=>$vpsId ? $panelUrl.'/console.php?vps_id='.urlencode((string)$vpsId) : '',
        'sso_enabled'=>($params['configoption9'] ?? '') === 'on',
        'vnc_enabled'=>($params['configoption11'] ?? '') === 'on',
        'graphs_enabled'=>($params['configoption12'] ?? '') === 'on',
        'controls_enabled'=>($params['configoption13'] ?? '') === 'on',
        'root_password'=>$rootPassword,
        'stats'=>$stats,
        'local'=>$local,
        'brand_primary'=>$brandPrimary,
        'brand_primary_tint_bg'=>$brandPrimaryTintBg,
        'brand_primary_tint_border'=>$brandPrimaryTintBorder,
        'brand_primary_icon_bg'=>$brandPrimaryIconBg,
        'brand_secondary'=>$brandSecondary,
        'brand_company'=>$brandCompany,
        'brand_logo'=>$brandLogo,
        'brand_custom_css'=>$brandCustomCss,
        'display_hostname'=>$displayHostname,
        'display_ip'=>$displayIp,
        'display_ipv6'=>$displayIpv6,
        'all_ips'=>$allIps,
        'display_os'=>$displayOs,
        'os_options'=>_hgpc_res_groupOptions($catalog, 'PC OS Version'),
        'os_options_json'=>json_encode(_hgpc_res_groupOptions($catalog, 'PC OS Version'), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT),
        'brand'=>$brand,
        
        'hgpc_config_json'=>json_encode([
            'password'=>$rootPassword,
            'serviceId'=>$params['serviceid'] ?? '',
            'consoleUrl'=>$vpsId ? $panelUrl.'/console.php?vps_id='.urlencode((string)$vpsId) : '',
            'panelUrl'=>$panelUrl.'/client/',
            'brandPrimary'=>$brandPrimary,
            'osOptions'=>_hgpc_res_groupOptions($catalog, 'PC OS Version'),
        ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT),
    ]];
}

function hostgraber_partnercloud_reseller_ClientAreaCustomButtonArray(): array { return []; }
function hostgraber_partnercloud_reseller_ClientAreaAllowedFunctions(): array { return []; }
function hostgraber_partnercloud_reseller_AdminCustomButtonArray(): array { return ['Sync Status'=>'SyncStatus', 'Reset Password'=>'AdminResetPassword']; }
function hostgraber_partnercloud_reseller_AdminResetPassword(array $params): string {
    $result = _hgpc_res_doPasswordReset($params);
    
    return str_starts_with($result, 'Error:') ? $result : 'success';
}
function hostgraber_partnercloud_reseller_TestConnection(array $params): array {
    $r = _sr_res_api($params, 'health', 'GET');
    $meta = $GLOBALS['_hgpc_res_last_api_meta'] ?? [];
    $diagnostic = sprintf(
        ' URL=%s User=%s TokenLength=%d HTTP=%d',
        $meta['url'] ?? 'unknown',
        $meta['user'] ?? 'unknown',
        (int)($meta['token_length'] ?? 0),
        (int)($r['http_code'] ?? 0)
    );
    if (_sr_res_ok($r)) {
        return ['success' => true, 'error' => '', 'message' => 'PartnerCloud reseller API reachable.'];
    }
    $message = _sr_res_msg($r);
    if ((int)($r['http_code'] ?? 0) === 401) {
        $message = 'Unauthorized. Use the exact PartnerCloud reseller email in Username and that reseller API token in Password or Access Hash.';
    }
    $message .= $diagnostic;
    return ['success' => false, 'error' => $message, 'message' => $message];
}

function hostgraber_partnercloud_reseller_SyncStatus(array $params): string {
    $vpsId = _sr_res_getVpsId($params);
    if (!$vpsId) return 'Error: VPS ID not found. Check the service notes for a "HostGraber PartnerCloud VPS: <id>" marker.';
    $r = _sr_res_api($params, "whmcs/vps/{$vpsId}/sync", 'POST');
    if (!_sr_res_ok($r)) return 'Error: ' . _sr_res_msg($r);
    _hgpc_res_syncWhmcsFields($params, $vpsId);
    
    return 'success';
}

function hostgraber_partnercloud_reseller_AdminServicesTabFields(array $params): array {
    $vpsId = _sr_res_getVpsId($params);
    if (!$vpsId) {
        return ['PartnerCloud VPS' => 'Not provisioned'];
    }
    $r = _sr_res_api($params, "whmcs/vps/{$vpsId}/stats", 'GET');
    $state = $r['data']['local'] ?? [];
    $allIps = $r['data']['ips'] ?? [];
    $panelUrl = _hgpc_res_baseUrl($params);

    $ram = (int) ($state['ram_mb'] ?? 0);
    $backup = !empty($state['backup_enabled']) ? 'Enabled' : 'Disabled';
    $os = $state['os_label'] ?? trim(($state['os_name'] ?? '') . ' ' . ($state['os_version_name'] ?? ''));

    $fields = [
        'PartnerCloud VPS ID' => $vpsId,
        'Hostname' => $state['hostname'] ?? 'unknown',
        'Status' => trim(($state['status'] ?? 'unknown') . ' / ' . ($state['power_status'] ?? 'unknown')),
        'Primary IP' => $state['primary_ip'] ?? 'pending',
        'All IP Addresses' => $allIps ? implode(', ', array_map(
            static fn($ip) => $ip['ip'] . ($ip['is_primary'] ?? false ? ' (primary)' : ''),
            $allIps
        )) : '',
        'OS' => $os !== '' ? $os : 'unknown',
        'Application' => $state['application_label'] ?? ($state['application_name'] ?? ''),
        'CPU' => isset($state['cpu']) ? $state['cpu'] . ' vCPU' : 'unknown',
        'RAM' => $ram > 0 ? ($ram >= 1024 ? round($ram / 1024, 1) . ' GB' : $ram . ' MB') : 'unknown',
        'Disk' => isset($state['disk_gb']) ? $state['disk_gb'] . ' GB' : 'unknown',
        'Bandwidth' => isset($state['bandwidth_gb']) ? $state['bandwidth_gb'] . ' GB/mo' : 'unknown',
        'Location' => $state['location_label'] ?? ($state['location_name'] ?? 'unknown'),
        'Compute Node' => $state['node_label'] ?? '',
        'Backups' => $backup,
    ];
    if ($panelUrl !== '') {
        $fields['Portal Link'] = '<a href="' . htmlspecialchars($panelUrl . '/admin/vps.php?id=' . $vpsId, ENT_QUOTES) . '" target="_blank">Open in PartnerCloud admin</a>';
    }
    return array_filter($fields, static fn($v) => $v !== '' && $v !== null);
}

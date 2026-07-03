{literal}<style>
#hgpcApp{background:rgba(127,127,127,.035);border-radius:20px;padding:0;overflow:hidden}
#hgpcApp .hgpc-inner{padding:0rem}
#hgpcApp .hgpc-topbar{height:4px;width:100%}
#hgpcApp .card{border-radius:12px;border:1px solid rgba(127,127,127,.14);box-shadow:0 1px 2px rgba(0,0,0,.05);background:rgba(127,127,127,.025);transition:box-shadow .18s ease,transform .18s ease}
#hgpcApp .card:hover{box-shadow:0 6px 18px rgba(0,0,0,.08);transform:translateY(-1px)}
#hgpcApp .card-header{background:transparent;font-weight:700;font-size:.76rem;text-transform:uppercase;letter-spacing:.06em;border-bottom:1px solid rgba(127,127,127,.12);display:flex;align-items:center;gap:.45rem;opacity:.7;padding:.7rem 1rem}
#hgpcApp svg.i{width:14px;height:14px;flex:0 0 auto;vertical-align:-2px}
#hgpcApp svg.i-lg{width:18px;height:18px;flex:0 0 auto;opacity:.6}
#hgpcApp .hgpc-logo{width:50px;height:50px;object-fit:contain;border-radius:12px;background:#fff;padding:5px;border:1px solid rgba(0,0,0,.08);box-shadow:0 2px 6px rgba(0,0,0,.08)}
#hgpcApp h5{font-weight:800;letter-spacing:-.01em}

/* Stat tiles with colored icon badges */
#hgpcApp .stat-tile{display:flex;align-items:center;gap:.7rem;padding:1rem}
#hgpcApp .stat-icon-badge{width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex:0 0 auto}
#hgpcApp .stat-icon-badge svg{width:18px;height:18px}
#hgpcApp .stat-label{font-size:.68rem;letter-spacing:.05em;font-weight:700;opacity:.5;text-transform:uppercase}
#hgpcApp .stat-value{font-size:1.25rem;font-weight:800;line-height:1.15;margin-top:.1rem}

/* One unified chip system for every action button in the widget */
#hgpcApp .chip-grid{display:flex;flex-wrap:wrap;gap:.5rem}
#hgpcApp .hgpc-chip{display:flex;flex-direction:column;align-items:center;justify-content:center;gap:.3rem;width:72px;padding:.55rem .3rem;border-radius:10px;border:1px solid rgba(127,127,127,.18);background:rgba(127,127,127,.05);font-size:.64rem;font-weight:700;text-align:center;transition:all .15s ease;cursor:pointer;color:inherit}
#hgpcApp .hgpc-chip:hover{transform:translateY(-2px);box-shadow:0 6px 14px rgba(0,0,0,.1)}
#hgpcApp .hgpc-chip svg{width:16px;height:16px;opacity:.85;transition:opacity .15s ease}
#hgpcApp .hgpc-chip:hover svg{opacity:1}
#hgpcApp .hgpc-chip .chip-icon-wrap{width:28px;height:28px;border-radius:8px;display:flex;align-items:center;justify-content:center}
#hgpcApp .hgpc-chip--success{background:#19875414;border-color:#19875440}
#hgpcApp .hgpc-chip--success .chip-icon-wrap{background:#19875428;color:#198754}
#hgpcApp .hgpc-chip--success:hover{border-color:#19875470;background:#1987541f}
#hgpcApp .hgpc-chip--danger{background:#dc354514;border-color:#dc354540}
#hgpcApp .hgpc-chip--danger .chip-icon-wrap{background:#dc354528;color:#dc3545}
#hgpcApp .hgpc-chip--danger:hover{border-color:#dc354570;background:#dc35451f}
#hgpcApp .hgpc-chip--warn{background:#fd7e1414;border-color:#fd7e1440}
#hgpcApp .hgpc-chip--warn .chip-icon-wrap{background:#fd7e1428;color:#fd7e14}
#hgpcApp .hgpc-chip--warn:hover{border-color:#fd7e1470;background:#fd7e141f}
#hgpcApp .hgpc-chip--info{background:#0dcaf014;border-color:#0dcaf040}
#hgpcApp .hgpc-chip--info .chip-icon-wrap{background:#0dcaf028;color:#0dcaf0}
#hgpcApp .hgpc-chip--info:hover{border-color:#0dcaf070;background:#0dcaf01f}
#hgpcApp .hgpc-chip--neutral{background:rgba(127,127,127,.07);border-color:rgba(127,127,127,.22)}
#hgpcApp .hgpc-chip--neutral .chip-icon-wrap{background:rgba(127,127,127,.14);color:inherit;opacity:.75}
#hgpcApp .hgpc-chip:disabled,#hgpcApp .hgpc-chip:disabled:hover{opacity:.35;cursor:not-allowed;transform:none;box-shadow:none}
#hgpcApp .hgpc-chip:disabled svg{opacity:.5}
#hgpcApp .divider-v{width:1px;align-self:stretch;background:rgba(127,127,127,.15);margin:0 .15rem}
#hgpcApp .header-actions{display:flex;gap:.4rem}
#hgpcApp .header-actions .hgpc-chip{width:64px;padding:.45rem .25rem}
#hgpcApp .header-actions .hgpc-chip .chip-icon-wrap{width:24px;height:24px}
#hgpcApp .header-actions .hgpc-chip svg{width:14px;height:14px}

/* Icon-only ghost buttons (copy, show/hide password) */
#hgpcApp .ghost-btn{background:none;border:none;padding:4px 6px;border-radius:6px;opacity:.5;cursor:pointer;transition:opacity .15s ease,background .15s ease,color .15s ease;color:inherit;display:inline-flex;align-items:center}
#hgpcApp .ghost-btn:hover{opacity:1;background:rgba(127,127,127,.1)}
#hgpcApp .ghost-btn.success-flash{opacity:1;color:#198754}
#hgpcApp .value-row{display:flex;align-items:center;gap:.15rem}

#hgpcApp code{background:rgba(127,127,127,.1);padding:.4rem .65rem;border-radius:7px;font-size:.85rem;letter-spacing:.02em}
#hgpcApp .badge-status{font-size:.72rem;font-weight:700;padding:.4em .85em;border-radius:20px;display:inline-flex;align-items:center;gap:.35rem}
#hgpcApp .badge-status .dot{width:6px;height:6px;border-radius:50%;background:currentColor;animation:hgpcPulse 2s ease-in-out infinite}
@keyframes hgpcPulse{0%,100%{opacity:1}50%{opacity:.3}}
#hgpcApp .brand-chip{display:inline-flex;align-items:center;gap:.4rem;font-size:.66rem;opacity:.45;margin-top:.6rem}
#hgpcApp .brand-chip .swatch{width:8px;height:8px;border-radius:3px;border:1px solid rgba(0,0,0,.15)}
#hgpcApp .hgpc-modal-backdrop{position:fixed;inset:0;background:rgba(0,0,0,.55);z-index:1050;display:none;align-items:center;justify-content:center;padding:1rem;backdrop-filter:blur(2px)}
#hgpcApp .hgpc-chart{height:135px;width:100%}
#hgpcApp .chart-legend{display:flex;flex-wrap:wrap;gap:.9rem;margin-top:.5rem;font-size:.71rem;opacity:.7}
#hgpcApp .chart-legend span{display:inline-flex;align-items:center;gap:.3rem}
#hgpcApp .chart-legend i{width:8px;height:8px;border-radius:2px;display:inline-block}
#hgpcApp .hgpc-toast{position:fixed;right:16px;bottom:16px;z-index:1080;display:flex;align-items:center;gap:.5rem;min-width:240px;max-width:360px;box-shadow:0 10px 28px rgba(0,0,0,.2);border-radius:10px;padding:.7rem 1rem;transform:translateY(20px);opacity:0;transition:all .25s ease;pointer-events:none}
#hgpcApp .hgpc-toast.show{transform:translateY(0);opacity:1}
#hgpcApp .hgpc-toast .toast-dot{width:8px;height:8px;border-radius:50%;flex:0 0 auto}
#hgpcApp .section-title{font-size:.9rem;font-weight:800;margin:1.6rem 0 .6rem;display:flex;align-items:center;gap:.45rem;opacity:.75;text-transform:uppercase;letter-spacing:.04em}

</style>{/literal}
{assign var="hgpcStatusColor" value="#6c757d"}
{if $local.status === 'running'}{assign var="hgpcStatusColor" value="#198754"}{/if}
{if $local.status === 'suspended' || $local.status === 'error'}{assign var="hgpcStatusColor" value="#dc3545"}{/if}
{if $local.status === 'building' || $local.status === 'starting' || $local.status === 'stopping' || $local.status === 'restarting' || $local.status === 'reinstalling'}{assign var="hgpcStatusColor" value="#fd7e14"}{/if}

{* Power-state flags drive which chips are enabled - avoids letting someone
   Stop an already-stopped VPS, VNC into one that's off, etc. *}
{assign var="hgpcTransitioning" value=false}
{if $local.status === 'building' || $local.status === 'starting' || $local.status === 'stopping' || $local.status === 'restarting' || $local.status === 'reinstalling'}{assign var="hgpcTransitioning" value=true}{/if}
{assign var="hgpcSuspended" value=false}
{if $local.status === 'suspended' || $local.status === 'error'}{assign var="hgpcSuspended" value=true}{/if}
{assign var="hgpcRunning" value=false}
{if $local.status === 'running' || $local.power_status === 'on'}{assign var="hgpcRunning" value=true}{/if}
{assign var="hgpcOff" value=false}
{if $local.status === 'stopped' || $local.power_status === 'off'}{assign var="hgpcOff" value=true}{/if}
{assign var="hgpcLocked" value=false}
{if $hgpcTransitioning || $hgpcSuspended}{assign var="hgpcLocked" value=true}{/if}

{assign var="hgpcBrandBtn" value="background:$brand_primary_tint_bg;border-color:$brand_primary_tint_border;color:inherit"}
{assign var="hgpcBrandIcon" value="background:$brand_primary_icon_bg;color:$brand_primary"}
<div id="hgpcApp">
  <div class="hgpc-topbar" style="background:linear-gradient(90deg,{$brand_primary},{$brand_secondary})"></div>
  <div class="hgpc-inner">

  <div class="card mb-3">
    <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-3 p-3">
      <div class="d-flex align-items-center gap-3">
        {if $brand_logo}<img class="hgpc-logo" src="{$brand_logo}" alt="{$brand_company}">{/if}
        <div style="padding-left:10px;">
          <div class="d-flex align-items-center gap-2">
            <h5 class="mb-0">{$display_hostname}</h5>
            &nbsp;&nbsp;&nbsp;<span class="badge-status" style="background:{$hgpcStatusColor}22;color:{$hgpcStatusColor}"><span class="dot"></span>{$local.status|default:'unknown'|capitalize}</span>
          </div>
          <small class="opacity-75 d-block">{$display_ip}</small>
        </div>
      </div>
      <div class="header-actions">
        {if $sso_enabled && $vps_id}
        <button type="button" class="hgpc-chip hgpc-chip--brand" style="{$hgpcBrandBtn}" onclick="hgpcAction('sso')">
          <span class="chip-icon-wrap" style="{$hgpcBrandIcon}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8l4 4-4 4"/><line x1="22" y1="12" x2="10" y2="12"/><path d="M14 3H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h8"/></svg></span>
          Sign In
        </button>
        {/if}
        {if $vnc_enabled && $vps_id}
        <button type="button" class="hgpc-chip hgpc-chip--info" onclick="hgpcOpenConsole()" {if $hgpcOff || $hgpcLocked}disabled title="Console needs the VPS running"{/if}>
          <span class="chip-icon-wrap"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg></span>
          VNC
        </button>
        {/if}
        <button type="button" class="hgpc-chip hgpc-chip--neutral" onclick="hgpcRefresh()">
          <span class="chip-icon-wrap"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg></span>
          Refresh
        </button>
      </div>
    </div>
  </div>

  <div class="card mb-3">
    <div class="row g-0">
      <div class="col-6 col-md-3 border-end"><div class="stat-tile">
        <div class="stat-icon-badge" style="background:{$hgpcStatusColor}18;color:{$hgpcStatusColor}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.55a11 11 0 0 1 14.08 0"/><path d="M1.42 9a16 16 0 0 1 21.16 0"/><path d="M8.53 16.11a6 6 0 0 1 6.95 0"/><line x1="12" y1="20" x2="12.01" y2="20"/></svg></div>
        <div><div class="stat-label">Status</div><div class="stat-value" id="hgpcStatus">{$local.status|default:'unknown'|capitalize}</div></div>
      </div></div>
      <div class="col-6 col-md-3 border-end"><div class="stat-tile">
        <div class="stat-icon-badge" style="background:#0d6efd18;color:#0d6efd"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="7" y="7" width="10" height="10" rx="1"/><rect x="3" y="3" width="18" height="18" rx="2"/></svg></div>
        <div><div class="stat-label">CPU</div><div class="stat-value">{$local.cpu|default:'0'} vCPU</div></div>
      </div></div>
      <div class="col-6 col-md-3 border-end"><div class="stat-tile">
        <div class="stat-icon-badge" style="background:#20c99718;color:#20c997"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="9" width="20" height="6" rx="1"/><line x1="6" y1="9" x2="6" y2="15"/><line x1="14" y1="9" x2="14" y2="15"/></svg></div>
        <div><div class="stat-label">RAM</div><div class="stat-value">{$local.ram_mb|default:'0'} MB</div></div>
      </div></div>
      <div class="col-6 col-md-3"><div class="stat-tile">
        <div class="stat-icon-badge" style="background:#6f42c118;color:#6f42c1"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="12" x2="2" y2="12"/><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/></svg></div>
        <div><div class="stat-label">Disk</div><div class="stat-value">{$local.disk_gb|default:'0'} GB</div></div>
      </div></div>
    </div>
  </div>

  {if $controls_enabled}
  <div class="card mb-3">
    <div class="card-header"><svg class="i" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 21v-7"/><path d="M4 10V3"/><path d="M12 21v-9"/><path d="M12 8V3"/><path d="M20 21v-5"/><path d="M20 12V3"/><path d="M1 14h6"/><path d="M9 8h6"/><path d="M17 16h6"/></svg>VPS Controls</div>
    <div class="card-body">
      <div class="chip-grid">
        <button type="button" class="hgpc-chip hgpc-chip--success" onclick="hgpcAction('start')" {if $hgpcRunning || $hgpcLocked}disabled{/if}>
          <span class="chip-icon-wrap"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="5 3 19 12 5 21 5 3"/></svg></span>Start
        </button>
        <button type="button" class="hgpc-chip hgpc-chip--danger" onclick="hgpcAction('stop')" {if $hgpcOff || $hgpcLocked}disabled{/if}>
          <span class="chip-icon-wrap"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="5" width="14" height="14" rx="2"/></svg></span>Stop
        </button>
        <button type="button" class="hgpc-chip hgpc-chip--warn" onclick="hgpcAction('restart')" {if $hgpcOff || $hgpcLocked}disabled{/if}>
          <span class="chip-icon-wrap"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg></span>Restart
        </button>
        <div class="divider-v"></div>
        <button type="button" class="hgpc-chip hgpc-chip--danger" onclick="hgpcOpenModal('reinstall')" {if $hgpcLocked}disabled{/if}>
          <span class="chip-icon-wrap"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-3.5-7.14"/><polyline points="21 3 21 9 15 9"/></svg></span>Reinstall
        </button>
        <button type="button" class="hgpc-chip hgpc-chip--info" onclick="hgpcOpenModal('rescue')" {if $hgpcLocked}disabled{/if}>
          <span class="chip-icon-wrap"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="4"/><line x1="4.93" y1="4.93" x2="9.17" y2="9.17"/><line x1="14.83" y1="14.83" x2="19.07" y2="19.07"/><line x1="14.83" y1="9.17" x2="19.07" y2="4.93"/><line x1="4.93" y1="19.07" x2="9.17" y2="14.83"/></svg></span>Rescue
        </button>
        <button type="button" class="hgpc-chip hgpc-chip--neutral" onclick="hgpcOpenModal('hostname')" {if $hgpcLocked}disabled{/if}>
          <span class="chip-icon-wrap"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg></span>Hostname
        </button>
        <button type="button" class="hgpc-chip hgpc-chip--neutral" onclick="hgpcOpenModal('password')" {if $hgpcLocked}disabled{/if}>
          <span class="chip-icon-wrap"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="7.5" cy="15.5" r="5.5"/><path d="M21 2l-9.6 9.6"/><path d="M15.5 7.5L18 5l3 3-2.5 2.5"/></svg></span>Password
        </button>
      </div>
      {if $hgpcLocked}<div class="small opacity-60 mt-2">{if $hgpcSuspended}This VPS is suspended - controls are unavailable.{else}Controls are locked while the VPS is {$local.status}.{/if}</div>{/if}
    </div>
  </div>
  {/if}

  <div class="row g-2 mb-3">
    <div class="col-md-7">
      <div class="card h-100">
        <div class="card-header"><svg class="i" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>Access Details</div>
        <div class="card-body">
          <div class="stat-label mb-1">Root Password</div>
          <div class="value-row mb-3">
            <code id="hgpcPass">&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;</code>
            <button type="button" class="ghost-btn" id="hgpcPassToggle" onclick="hgpcTogglePassword()" title="Show password"><svg class="i" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
            <button type="button" class="ghost-btn" onclick="hgpcCopy('hgpcPass', this)" title="Copy password"><svg class="i" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg></button>
          </div>
          <div class="stat-label mb-1">Operating System</div>
          <div class="fw-semibold">{$display_os}</div>
        </div>
      </div>
    </div>
    <div class="col-md-5">
      <div class="card h-100">
        <div class="card-header"><svg class="i" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.55a11 11 0 0 1 14.08 0"/><path d="M1.42 9a16 16 0 0 1 21.16 0"/><path d="M8.53 16.11a6 6 0 0 1 6.95 0"/><line x1="12" y1="20" x2="12.01" y2="20"/></svg>Network</div>
        <div class="card-body">
          <div class="stat-label mb-1">Primary IP</div>
          <div class="value-row mb-3">
            <span class="fw-semibold" id="hgpcIp">{$local.primary_ip|default:'pending'}</span>
            <button type="button" class="ghost-btn" onclick="hgpcCopy('hgpcIp', this)" title="Copy IP"><svg class="i" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg></button>
          </div>
          <div class="stat-label mb-1">Bandwidth</div>
          <div class="fw-semibold">{$local.bandwidth_gb|default:'0'} GB/mo</div>
        </div>
      </div>
    </div>
  </div>

  {if $graphs_enabled}
  <div class="section-title"><svg class="i-lg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>Resource Usage</div>
  <div class="row g-2 mb-3">
    <div class="col-md-6"><div class="card"><div class="card-header">CPU</div><div class="card-body"><canvas class="hgpc-chart" id="chartCpu"></canvas><div class="chart-legend" id="chartCpuLegend"></div></div></div></div>
    <div class="col-md-6"><div class="card"><div class="card-header">RAM</div><div class="card-body"><canvas class="hgpc-chart" id="chartRam"></canvas><div class="chart-legend" id="chartRamLegend"></div></div></div></div>
    <div class="col-md-6"><div class="card"><div class="card-header">Disk / Network</div><div class="card-body"><canvas class="hgpc-chart" id="chartNet"></canvas><div class="chart-legend" id="chartNetLegend"></div></div></div></div>
    <div class="col-md-6"><div class="card"><div class="card-header">Bandwidth</div><div class="card-body"><canvas class="hgpc-chart" id="chartBw"></canvas><div class="chart-legend" id="chartBwLegend"></div></div></div></div>
  </div>
  {/if}


  <div class="hgpc-modal-backdrop" id="hgpcModal">
    <div class="card" style="width:min(520px,100%)">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span id="hgpcModalTitle">Action</span>
        <button type="button" class="btn-close" aria-label="Close" onclick="hgpcCloseModal()"></button>
      </div>
      <div class="card-body" id="hgpcModalBody"></div>
    </div>
  </div>
  <div class="hgpc-toast" id="hgpcToast" role="status"><span class="toast-dot" id="hgpcToastDot"></span><span id="hgpcToastBody"></span></div>
  </div>
</div>
{literal}<script>
const HGPC = {/literal}{$hgpc_config_json nofilter}{literal};
</script>{/literal}
{literal}<script>
const HGPC_ICON = {
  reinstall: '<svg class="i" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-3.5-7.14"/><polyline points="21 3 21 9 15 9"/></svg>',
  disk: '<svg class="i" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="4"/></svg>',
  rescue: '<svg class="i" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="4"/><line x1="4.93" y1="4.93" x2="9.17" y2="9.17"/><line x1="14.83" y1="14.83" x2="19.07" y2="19.07"/><line x1="14.83" y1="9.17" x2="19.07" y2="4.93"/><line x1="4.93" y1="19.07" x2="9.17" y2="14.83"/></svg>',
  hostname: '<svg class="i" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>',
  key: '<svg class="i" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="7.5" cy="15.5" r="5.5"/><path d="M21 2l-9.6 9.6"/><path d="M15.5 7.5L18 5l3 3-2.5 2.5"/></svg>',
  check: '<svg class="i" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>',
  eye: '<svg class="i" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>',
  eyeOff: '<svg class="i" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.94 10.94 0 0 1 12 20c-7 0-11-8-11-8a18.5 18.5 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>'
};
function hgpcToast(msg, tone){
  tone = tone || 'info';
  const colors = {success:'#198754', danger:'#dc3545', warn:'#fd7e14', info:'#0d6efd'};
  const t=document.getElementById('hgpcToast'), b=document.getElementById('hgpcToastBody'), d=document.getElementById('hgpcToastDot');
  b.textContent=msg; d.style.background=colors[tone]||colors.info;
  t.classList.add('show');
  clearTimeout(window._hgpcToastTimer);
  window._hgpcToastTimer=setTimeout(()=>t.classList.remove('show'),3800);
}
async function hgpcPost(action, data={}){
  const body=new URLSearchParams(Object.assign({hgpc_action:action},data));
  const r=await fetch(location.href,{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body});
  let j;try{j=await r.json()}catch(e){j={success:false,error:'Invalid response'}}
  if(action!=='usage'){
    if(!j.success) hgpcToast(j.error||j.message||'Action failed','danger');
    else hgpcToast(j.message||'Done','success');
  }
  return j;
}
function hgpcOpenSso(token){window.open(HGPC.panelUrl.replace(/\/client\/?$/,'')+'/auth/sso.php?token='+encodeURIComponent(token),'_blank');}
async function hgpcAction(action){
  const j=await hgpcPost(action);
  if(action==='sso'&&j.token){hgpcOpenSso(j.token);}
  if(action==='sync'||action==='start'||action==='stop'||action==='restart') setTimeout(()=>location.reload(),1200);
}
async function hgpcOpenConsole(){
  const j = await hgpcPost('vnc-sso');
  if (!j.success || !j.token) { return; }
  hgpcOpenSso(j.token);
}
function hgpcCopy(id, btn){
  const text = document.getElementById(id).textContent;
  navigator.clipboard.writeText(text).then(()=>{
    btn.classList.add('success-flash');
    const original = btn.innerHTML;
    btn.innerHTML = HGPC_ICON.check;
    hgpcToast('Copied to clipboard','success');
    setTimeout(()=>{ btn.classList.remove('success-flash'); btn.innerHTML = original; }, 1500);
  }).catch(()=>hgpcToast('Could not copy - select and copy manually','danger'));
}
function hgpcTogglePassword(){
  const el=document.getElementById('hgpcPass'), t=document.getElementById('hgpcPassToggle');
  const hidden = el.textContent.indexOf('\u2022')!==-1;
  el.textContent = hidden ? (HGPC.password||'No password stored') : '\u2022\u2022\u2022\u2022\u2022\u2022\u2022\u2022\u2022\u2022\u2022\u2022';
  t.innerHTML = hidden ? HGPC_ICON.eyeOff : HGPC_ICON.eye;
  t.title = hidden ? 'Hide password' : 'Show password';
}
function hgpcOpenModal(type){
  const m=document.getElementById('hgpcModal'), b=document.getElementById('hgpcModalBody'), t=document.getElementById('hgpcModalTitle');
  const brandBtn='background-color:'+HGPC.brandPrimary+';border-color:'+HGPC.brandPrimary+';color:#fff;border-radius:8px;font-weight:700;padding:.5rem 1rem;border-width:1px;display:inline-flex;align-items:center;gap:.4rem';
  let html='';
  if(type==='reinstall'){
    t.textContent='Reinstall OS';
    html='<select id="hgpcOs" class="form-select mb-2">'+HGPC.osOptions.map(o=>'<option value="'+o.value+'">'+o.name+'</option>').join('')+'</select><p class="small opacity-75">This erases all data.</p><button type="button" class="btn btn-sm btn-danger" onclick="hgpcReinstall()">'+HGPC_ICON.reinstall+' Reinstall</button>';
  }
  if(type==='rescue'){
    t.textContent='Boot / Rescue';
    html='<div class="d-flex gap-2"><button type="button" class="btn btn-sm btn-outline-secondary" onclick="hgpcBoot(&quot;disk&quot;)">'+HGPC_ICON.disk+' Boot from Disk</button><button type="button" class="btn btn-sm btn-warning" onclick="hgpcBoot(&quot;rescue&quot;)">'+HGPC_ICON.rescue+' Boot from Rescue</button></div>';
  }
  if(type==='hostname'){
    t.textContent='Change Hostname';
    html='<input id="hgpcHostname" class="form-control mb-2" placeholder="server.example.com"><button type="button" style="'+brandBtn+'" onclick="hgpcHostname()">'+HGPC_ICON.hostname+' Save Hostname</button>';
  }
  if(type==='password'){
    t.textContent='Reset Root Password';
    html='<p class="small opacity-75">New password will be generated. The new password will be shown here once ready.</p><button type="button" style="'+brandBtn+'" onclick="hgpcPassword()">'+HGPC_ICON.key+' Reset Password</button>';
  }
  b.innerHTML=html;m.style.display='flex';
}
function hgpcCloseModal(){document.getElementById('hgpcModal').style.display='none';}
async function hgpcReinstall(){const v=document.getElementById('hgpcOs').value;let p=v.split(':');await hgpcPost('reinstall',{os_id:p[0]||'',os_version_id:p[1]||p[0]||''});hgpcCloseModal();}
async function hgpcBoot(mode){await hgpcPost('boot',{boot_mode:mode});hgpcCloseModal();}
async function hgpcHostname(){await hgpcPost('hostname',{hostname:document.getElementById('hgpcHostname').value});hgpcCloseModal();setTimeout(()=>location.reload(),1000);}
async function hgpcPassword(){
  const j = await hgpcPost('password');
  if (j.success) {
    const actual = (j.data && j.data.password) ? j.data.password : '';
    if (actual) { HGPC.password = actual; document.getElementById('hgpcPass').textContent = actual; document.getElementById('hgpcPassToggle').innerHTML = HGPC_ICON.eyeOff; }
  }
  hgpcCloseModal();
}
function hgpcLegend(id, items){const el=document.getElementById(id+'Legend');if(!el)return;el.innerHTML=items.map(it=>'<span><i style="background:'+it.color+'"></i>'+it.label+'</span>').join('');}
function drawMultiLine(id, series){
  const c=document.getElementById(id);if(!c)return;
  const ctx=c.getContext('2d'),w=c.width=c.clientWidth*devicePixelRatio,h=c.height=c.clientHeight*devicePixelRatio;
  ctx.clearRect(0,0,w,h);
  ctx.strokeStyle='rgba(127,127,127,.15)';
  for(let i=1;i<5;i++){ctx.beginPath();ctx.moveTo(0,h*i/5);ctx.lineTo(w,h*i/5);ctx.stroke();}
  const normalized = series.map(s=>({color:s.color, values:(s.values&&s.values.length?s.values:[0,0,0,0]).slice(-24).map(x=>Number(x.y||x.value||x)||0)}));
  const max = Math.max(1, ...normalized.reduce((a,s)=>a.concat(s.values),[]));
  normalized.forEach(s=>{
    ctx.strokeStyle=s.color;ctx.lineWidth=2.5*devicePixelRatio;ctx.beginPath();
    s.values.forEach((v,i)=>{const x=i*Math.max(1,w/(s.values.length-1)),y=h-(v/max*h*.85)-h*.08;if(i)ctx.lineTo(x,y);else ctx.moveTo(x,y);});
    ctx.stroke();
  });
}
async function hgpcLoadGraphs(){
  try{
    const j=await hgpcPost('usage');
    if(!j.success)return;
    const d=j.data||{};const s=d.series||{};const m=d.metrics||{};
    drawMultiLine('chartCpu',[{values:s.cpu,color:HGPC.brandPrimary||'#0d6efd'}]);
    hgpcLegend('chartCpu',[{label:'CPU %',color:HGPC.brandPrimary||'#0d6efd'}]);
    drawMultiLine('chartRam',[{values:s.memory,color:'#20c997'}]);
    hgpcLegend('chartRam',[{label:'Memory %',color:'#20c997'}]);
    drawMultiLine('chartNet',[{values:s.network_read_kb,color:'#0dcaf0'},{values:s.disk_read_kb,color:'#6f42c1'}]);
    hgpcLegend('chartNet',[{label:'Network (KB)',color:'#0dcaf0'},{label:'Disk Read (KB)',color:'#6f42c1'}]);
    drawMultiLine('chartBw',[{values:[m.bandwidth_used_gb||0,m.bandwidth_used_gb||0],color:'#ffc107'},{values:[m.bandwidth_limit_gb||0,m.bandwidth_limit_gb||0],color:'rgba(127,127,127,.5)'}]);
    hgpcLegend('chartBw',[{label:'Used (GB)',color:'#ffc107'},{label:'Limit (GB)',color:'rgba(127,127,127,.5)'}]);
  }catch(e){}
}
function hgpcRefresh(){hgpcAction('sync');}
hgpcLoadGraphs();
setInterval(hgpcLoadGraphs, 15000);
</script>{/literal}

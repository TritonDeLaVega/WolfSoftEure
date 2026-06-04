from pathlib import Path
import re
path = Path(r'c:\Users\Sola\AppData\Roaming\Code\User\workspaceStorage\3ae201932347fe003fc4eb2ac99d2ebd\GitHub.copilot-chat\chat-session-resources\3ea72afd-b887-4cc3-b295-9b186e46f964\call_or2JEldcImPNFY5GsQDHGftf__vscode-1780567715531\content.txt')
text = path.read_text(encoding='utf-8', errors='ignore')
m = re.search(r'<ul[^>]*class="[^\"]*products[^\"]*wse-products-grid[^\"]*"[^>]*>(.*?)</ul>', text, re.S)
if not m:
    print('NO_UL')
else:
    print('FOUND_UL')
    snippet = m.group(0)
    lines = snippet.splitlines()
    for i, line in enumerate(lines[:80], 1):
        print(f'{i}: {line}')
    print('---')
    print('TOTAL lines', len(lines))
    print('li.product count', len(re.findall(r'<li[^>]*class="[^\"]*product[^\"]*"', snippet)))

import re

with open('src/contacts.html', 'r', encoding='utf-8') as f:
    content = f.read()

contacts_match = re.search(r'  <!-- ============ CONTACT DETAILS ============ -->.*?</section>\n', content, re.DOTALL)
lead_match = re.search(r'  <!-- ============ LEAD CAPTURE \(quiz teaser \+ contact form\) ============ -->.*?</section>\n', content, re.DOTALL)

if contacts_match and lead_match:
    contacts_html = contacts_match.group(0)
    lead_html = lead_match.group(0)
    
    # Remove both from content
    content = content.replace(contacts_html, '')
    content = content.replace(lead_html, '')
    
    # Add disclaimer
    disclaimer = '            <p class="ac-small" style="margin-top:var(--space-4); margin-bottom:var(--space-3); color:var(--muted); text-align:center;">By submitting this request, you agree to our <a href="terms.html" style="color:inherit; text-decoration:underline;">Terms</a> and <a href="privacy-policy.html" style="color:inherit; text-decoration:underline;">Privacy Policy</a>.</p>'
    lead_html = lead_html.replace('<button class="ac-btn', disclaimer + '\n            <button class="ac-btn')
    
    # Insert LEAD then CONTACTS after the HERO section
    # The HERO section ends before CONTACT DETAILS which we already removed.
    # We can just append them after PAGE HERO.
    hero_pattern = re.compile(r'(<!-- ============ PAGE HERO ============ -->.*?</section>\n)', re.DOTALL)
    
    def repl(m):
        return m.group(1) + '\n' + lead_html + '\n' + contacts_html
        
    final_content = hero_pattern.sub(repl, content)
    
    # clean up extra newlines at EOF
    final_content = final_content.strip() + '\n'
    
    with open('src/contacts.html', 'w', encoding='utf-8') as f:
        f.write(final_content)
    print("Done")
else:
    print("Failed to match")

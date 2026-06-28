import glob
import re

html_files = glob.glob('site/*.html')

for filepath in html_files:
    with open(filepath, 'r') as f:
        content = f.read()
        
    cta_match = re.search(r'<section class="ac-section (ac-section--(?:paper|cream|navy))" id="cta"', content)
    if cta_match:
        cta_bg = cta_match.group(1)
        
        # Find all sections before CTA
        sections_before = re.findall(r'<section class="(?:ac-section|ac-page-hero)[^"]*(ac-(?:section|page-hero)--(?:paper|cream|navy))?"', content[:cta_match.start()])
        
        if sections_before:
            # The last match is the immediate preceding section
            prev_bg = sections_before[-1]
            if not prev_bg: # default if no modifier
                prev_bg = "ac-section--paper" # or depends on what it is
            
            # Extract just the color part
            if 'cream' in prev_bg:
                prev_color = 'cream'
            elif 'navy' in prev_bg:
                prev_color = 'navy'
            else:
                prev_color = 'paper'
                
            cta_color = cta_bg.split('--')[1]
            
            if prev_color != cta_color:
                print(f"{filepath}: CTA is {cta_color}, but previous is {prev_color}")

<x-emails.layout 
    :user="$admin"
    :subject="'New Brand Added - ' . config('app.name')"
    :title="'A New Brand Has Been Added'"
    :body="'A new brand, ' . $brand->name . ', has been added to the platform. Click below to view brand details.'"
    :button_url="url('/admin/brands/' . $brand->id)"
    :button_text="'View Brand'"
/>

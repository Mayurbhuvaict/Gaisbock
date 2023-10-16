<?php declare(strict_types=1);


namespace Pluszwei\FaqManager\Core\Content\Category;


use Shopware\Core\System\SalesChannel\SalesChannelEntity;

class CategoryBreadcrumbBuilder
{
    public function build(
        CategoryEntity $category,
        ?SalesChannelEntity $salesChannel = null,
        ?string $navigationCategoryId = null): ?array
    {
        $categoryBreadcrumb = $category->getPlainBreadcrumb();

        // If the current SalesChannel is null ( which refers to the default template SalesChannel) or
        // this category has no root, we return the full breadcrumb
        if ($salesChannel === null && $navigationCategoryId === null) {
            return $categoryBreadcrumb;
        }

        $entryPoints = [
            $navigationCategoryId,
        ];

        if ($salesChannel !== null) {
            $entryPoints[] = $salesChannel->getNavigationCategoryId();
            $entryPoints[] = $salesChannel->getServiceCategoryId();
            $entryPoints[] = $salesChannel->getFooterCategoryId();
        }

        $entryPoints = array_filter($entryPoints);

        $keys = array_keys($categoryBreadcrumb);

        foreach ($entryPoints as $entryPoint) {
            // Check where this category is located in relation to the navigation entry point of the sales channel
            $pos = array_search($entryPoint, $keys, true);

            if ($pos !== false) {
                // Remove all breadcrumbs preceding the navigation category
                return \array_slice($categoryBreadcrumb, $pos + 1);
            }
        }

        return $categoryBreadcrumb;
    }
}
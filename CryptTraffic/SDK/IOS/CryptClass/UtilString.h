//
//  UtilString.h
//  EASY vision 1.0
//
//  Created by 葛新伟 on 15/10/28.
//  Copyright © 2015年 Xuyang Gordon Wang. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface UtilString : NSObject

/**
 *  获取urlencode string
 *
 *  @param baseStr 原string
 *
 *  @return 编码后的string
 */
+ (NSString *)stringByURLEncode:(NSString *)baseStr;

/**
 *  转换成json string
 *
 *  @param object 需要转换的对象
 *
 *  @return 转换后的jsonstring
 */
+ (NSString*)DataTOjsonString:(id)object;

/**
 *  把json string 转换成对象
 *
 *  @param json json string
 *
 *  @return 转换后的对象
 */
+ (id)jsonToData:(NSString *)json;

@end
